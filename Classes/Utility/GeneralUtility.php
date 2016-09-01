<?php
namespace Emagineurs\EAnnuaires\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Xavier Ley <xley@e-magineurs.com>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GeneralUtility {	

    /**
	 * ficheRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\FicheRepository
	 * @inject
	 */
	protected $ficheRepository;
	
    /**
	 * categorieRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\CategorieRepository
	 * @inject
	 */
	protected $categorieRepository;

    /**
	 * ficheRepository
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	
    public function getArrayForSearchForm($confArray, $settings){      
        $this->settings = $settings;
		// $this->ficheRepository->setSettings($settings, array('cObj' => $this->configurationManager->getContentObject()));	
		
		
        if($confArray['values']){
            return $confArray['values'];
        }
        
        if($confArray['table']){
            $result = $this->handleArrayFromSettings($confArray);             
        }
        
        if($confArray['excludeItems']){
            $result = $this->excludeItems($confArray,$result);
        }
        
        if($confArray['addItems']){
            $result = $result + $confArray['addItems'];
        }
                
		if(empty($confArray['noEmptyValue'])){
			$newArray = array(0 => '');
			//array_unshift($result,'');
		}		
		
		foreach($result as $key => $item){
			$newArray[$key] = $item;
		}
		
        return $newArray;
        //return $result;
    }
    
    public function handleArrayFromSettings($confArray){            
        $filtreConf = array(
            'table' => $confArray['table'],
            'orderBy' => $confArray['orderBy'],
            'andWhere' => $confArray['andWhere'],
            'groupBy' => $confArray['groupBy'],
            'selectFields' => $confArray['selectFields'],
            'doNotExplode' => $confArray['doNotExplode'],
            'inAllSites' => $confArray['inAllSites']
        );

        $pidTable = $this->getPidInArrayGeneration($filtreConf); 
        $filtreConf['selectFields'] .= ($pidTable[1] > 1) ? ', '.$pidTable[0].'.pid': ', pid';

        if(!$confArray['mainCat']){
            $filtreConf['parentField'] = ($confArray['pid']) ? 'pid = '.$confArray['pid'].' AND 1 ' : ' 1 ';
            $fieldArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$filtreConf['selectFields']);

            if($confArray['recursive'] && $confArray['parentValue'] && $confArray['parentField']){
                $result = $this->getRecursiveArray($filtreConf,$confArray,$fieldArray);               
            } else {
                $result = $this->getStandardArray($filtreConf,$fieldArray); 
            }
        } else {
            $result = $this->getMainCatArray($confArray,$filtreConf);
        }
        
        return $result;
    }

    public function getPidInArrayGeneration($filtreConf){
		$pidTable = array();
		$pidTableArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$filtreConf['table']);

        $pidTable[0] = $pidTableArray[0];
		$pidTable[1] = count($pidTableArray);
		
        return $pidTable;
    }
    
    public function getMainCatArray($confArray,$filtreConf){
        $result = array();
        $resultArray = array();
        
        $fieldArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$filtreConf['selectFields']);

        $catList =  !empty($confArray['parentValue']) ? $confArray['parentValue'] : $this->settings['filtre']['categories']['value'];
        $confArray['recursive'] =  !empty($confArray['recursive']) ? $confArray['recursive'] : $this->settings['filtre']['categories']['recursive'];
        $arrayCat = explode(',',$catList);

        $i = 0;
        foreach($arrayCat as $cat){
            $confArray['parentValue'] = intval($cat);
            $result[$i] = $this->getRecursiveArray($filtreConf,$confArray,$fieldArray);
			
            if($confArray['mainCat'] && $confArray['displayParent'] != '1'){
                unset($result[$i][$cat]);                
            }
            $i++;
        }

        if(count($result) > 1){
            $i = 0;
            $test = array();
            while($i < count($result)){   
//                $resultArray[] = array_merge($result[$i],$result[$i+1]);
                $resultArray[] = $result[$i]+$result[$i+1];
                $i = $i+2;
            }
        } else if(is_array($result)){
            $resultArray = $result[0];
        }
                
        if(is_array($resultArray[0])){
            return array_unique($resultArray[0]);
        } else {
            return array_unique($resultArray);
        }
        
    }


    public function excludeItems($confArray,$result){
        $arrayToExclude = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$confArray['excludeItems']);

        foreach($arrayToExclude as $itemToExclude){
            unset($result[$itemToExclude]);
        }
        
        return $result;
    }
    
    public function getStandardArray($filtreConf,$fieldArray){
        $result = array();
        
        $arrayItem = $this->ficheRepository->getChildrenRecords($filtreConf,$filtreConf['parentField']);

        if(!empty($arrayItem) && is_array($arrayItem)){
			$fieldArrayTemp = array();
			
			$fieldArrayTemp[0] = array_search(reset($arrayItem[0]), $arrayItem[0]);
			$fieldArrayTemp[1] = array_search(next($arrayItem[0]), $arrayItem[0]);
			$fieldArrayTemp[2] = end($fieldArray);
			
			$fieldArray = $fieldArrayTemp;
		}
		
        if(is_array($fieldArray) && !empty($fieldArray)){
            foreach($fieldArray as $key => $field){
                $finalFieldArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('.',$field);
                $finalField = end($finalFieldArray);

                $fieldArray[$key] = $finalField;
            }
        }

        foreach($arrayItem as $item){
			if($filtreConf['inAllSites'] || $this->checkRootPage($item['pid'])){
				$result = $this->fillStandardArray($item,$fieldArray,$result,$filtreConf['doNotExplode']);
			}
        }
		
        $result = array_unique($result);
				
        return $result;
    }
	
	public function fillStandardArray($item,$fieldArray,$result,$doNotExplode = FALSE){
		//si j'ai une valeur et une virgule dans mes clé et valeur (surtout pour le cas d'une liste à choix multiple dans le formulaire)
		if((strpos($item[$fieldArray[0]], ",")!==false AND strpos($item[$fieldArray[1]], ",")!==false) && $doNotExplode === FALSE){
			$fa1 = explode(",", $item[$fieldArray[1]]);
			foreach($fa1 as $fa_value){
				$result[] = $fa_value;
			}
		}else {
			$result[$item[$fieldArray[0]]] = $item[$fieldArray[1]];
		}
		
		return $result;
	}
    
    public function getRecursiveArray($filtreConf,$confArray,$fieldArray){ 
        $res = array();
        $currentArray = array();
        
        $filtreConf['recursive'] = $confArray['recursive'];
        $filtreConf['parentField'] = ($confArray['pid']) ? 'pid = '.$confArray['pid'].' AND '.$confArray['parentField'].' ' : $confArray['parentField'];
	
        
        if(intval($filtreConf['recursive']) > 0){   
            foreach($fieldArray as $key => $field){        
                $currentArray[$key] = $this->generateRecursiveArray($filtreConf,$field,$key,$res,$confArray,$currentArray,$fieldArray);
            }
        } else {
            $currentArray[0][0] = $confArray['parentValue'];
        }
        
        if($confArray['mainCat'] && $confArray['displayParent'] == '1'){
            $currentArray = $this->addMainCat($currentArray);
        }
		
		$result = array();
		if(is_array($currentArray[2]) && !empty($currentArray[2]) && count($currentArray[2]) > 0){   
			foreach($currentArray[2] as $key => $pid){
				if($confArray['inAllSites'] || $this->checkRootPage($pid)){
					$result[$currentArray[0][$key]] = $currentArray[1][$key];
				}
			}                   
		}

       $result = $this->removeExcessIndentation($result);
                
        return $result;
    }
    
    public function addMainCat($currentArray){
        $idMainCat = $currentArray[0][0];
		
        $mainCat = $this->categorieRepository->findByUid($idMainCat);
                
        $currentArray[1][0] = $mainCat->getTitle();  
        $currentArray[2][0] = strval($mainCat->getPid()); 
                        
        return $currentArray;
    }
    
    public function removeExcessIndentation($result){
        $i = 0;
        foreach($result as $key => $chaine){
            $currentSpaces = substr_count($chaine, '&nbsp;&nbsp;&nbsp;');
            
            if($currentSpaces > 0){
                if($i > 0){
                    if($countSpace > $currentSpaces){  
                        $countSpace = $currentSpaces; 
                    }
                } else {
                    $countSpace = $currentSpaces; 
                }
                
                $i++;    
            } else {
                return $result;
            }
         
            $result[$key] = $this->removeIndentation($chaine,$countSpace);
        }
                
        return $result;
    }
    
    public function removeIndentation($chaine,$countSpace){   
        while($countSpace > 0){
            $chaine = substr($chaine,18);
            $countSpace--;
        }
        
        return $chaine;
    }
    
    public function generateRecursiveArray($filtreConf,$field,$key,$res,$confArray,$currentArray,$fieldArray){
        $filtreConf['returnedField'] = $field;
        $select = $key;

        $res[$field] = $this->ficheRepository->getRecursiveValue($confArray['parentValue'], $filtreConf, $select);
                
        if($key === 1 && $confArray['displayParent'] != '1'){
            $res[$field][1] = $this->replaceFirstValue($res[$field][1],$filtreConf,$fieldArray[0]);
        }

        $currentArray[$key] = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('||',$res[$field][1]); 
        
        return $currentArray[$key];
    }
    
    public function replaceFirstValue($value,$filtreConf,$field){
        $valueArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('||',$value);
        
        $select = $filtreConf['returnedField'];
        $table = $filtreConf['table'];
        $where = $field.' = '.$valueArray[0];
                        
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
        
        $valueArray[0] = $req[0][$filtreConf['returnedField']]; 
        
        return implode('||',$valueArray);
    }
    
    function checkRootPage($page){
        $this->pageRepo = $this->objectManager->get('TYPO3\CMS\Frontend\Page\PageRepository');
        
        $currentPage = $GLOBALS['TSFE']->id;
        
        $currentRootLine = $this->getSiteRootPage($currentPage); 
        
        $testedRootLine = $this->getSiteRootPage($page); 
        
        if($currentRootLine[0]['uid'] == $testedRootLine[0]['uid']){
            return true;        
        }    
    }
    
    public function getSiteRootPage($page){
        $rootLine = $this->pageRepo->getRootLine($page);
        
        sort($rootLine);
        
        foreach($rootLine as $key => $item){
            if($item['is_siteroot']){     
                return $rootLine; 
            } else {
                $rootLine = array_splice($rootLine,1,(count($rootLine)-1));
            }
        }
    }	
		
	public static function createTemplateFluid($path,$assign,$tpl = 0){     
		// On instancie un nouveau template
        $view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');

		// On défini le chemin du fichier a utiliser comme template
        if($tpl == 1){
            $view->setTemplateSource($path);
        } else {
            $mainTemplate = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path);
            $view->setTemplatePathAndFilename($mainTemplate);
        }
		
		// On assigne les données a passés
		$view->assignMultiple($assign);

		// On stocke le rendu du template dans la variable $contenu 
        $contenu = $view->render();   
		
		return $contenu;
	}

    public static function removeDotsInConf($array){
        foreach($array as $key => $setting){
            if(strpos($key,'.') !== false){
                $newKey = substr($key,0,-1);    
                $array[$newKey] = self::removeDotsInConf($setting);
                unset($array[$key]);
            }
        }
        
        return $array;
    }

}