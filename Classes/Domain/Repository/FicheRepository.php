<?php
namespace Emagineurs\EAnnuaires\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 * (c) 2013 Xavier Ley <xley@e-magineurs.com>
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
 * use Emagineurs\EAnnuaires\Domain\Model\Fiche;
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FicheRepository extends AbstractRepository {
    
	/**
	* @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	*/
	protected $configurationManager;

	/**
	* @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	* @return void
	*/
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}
    
    /**
	 * Permet d'accéder aux settings dans le repo si appelé dans le controller.
     * Permet également de définir des variables spécifiques à chaque vue via la variable params
	 *
	 * @param array $settings
	 * @param array $params
	 * @return void
	 */
	public function setSettings($settings, $params = array()){
		$this->settings = $settings;
		
		if(count($params) > 0){
			foreach($params as $key => $value){
				$this->$key = $value;
			}
		}
        
        $this->pageRepo = $this->objectManager->get('TYPO3\CMS\Frontend\Page\PageRepository');
        $this->fileRepository = $this->objectManager->get('TYPO3\CMS\Core\Resource\ResourceFactory');
	}
	
	// public function findByUid($uid){
		// if($this->settings['displayHidden'][$this->tableName]){
			// $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
			// $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled'));
		// }
			
		// $this->defaultQuerySettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface::class);
		// $this->defaultQuerySettings->setIgnoreEnableFields(TRUE);
		// $this->defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
			
		// return parent::findByUid($uid);
		// return $this->findByIdentifier($uid);
	// }
	
	public function findAll(){
		$query = $this->createQuery();

		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		
		return $query->execute();
	}
    
	public function findWithSettings($currentRequest = '', $count = 0, $rawResult = FALSE){
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
        // On applique a la liste les filtres des configurations
        $constraints = $this->getConstraintFromSettings($query);	
                
        // On applique a la liste les filtres de la recherche
        if($this->search){    
            $constraints = $this->getConstraintFromSearch($currentRequest,$constraints,$query);
        }
        
        if($constraints){
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }
                
        // Si, dans le controller,on a spéfifié que l'on voulait le nombre de résultat, en le renvoie au lieu de la liste
        // Sinon on applique les critères de tri et la limite
        if($count != 1){
            $this->setOrderingsQuery($query,$currentRequest);

            $this->setLimitQuery($query,$currentRequest);
        } else {
            return $query->count();
        }
             
		return $query->execute($rawResult);
    }
    
    public function setLimitQuery($query,$currentRequest){
        // On défini une limite à partir des configurations (TS ou flexform), si rien de défini on met par défaut a 5
        if($this->settings['nbElement']){
            $query->setLimit(intval($this->settings['nbElement']));
        }
        
        // On change l'offset de l'affichage si on a parametre "page", qui indique l'utilisation de la pagination
        if(isset($currentRequest['page']) && isset($this->settings['nbElement'])){
            $offset = (intval($currentRequest['page']) - 1)*(intval($this->settings['nbElement']));

            $query->setOffset($offset);
        }
    }
    
    public function setOrderingsQuery($query,$currentRequest = array()){
        // On défini ici le champ de tri
        if($this->settings['orderBy']){

            // Cas particulier pour le tri aléatoire
    		if ($this->settings['orderBy'] == 'aleatoire') {
                $query->setOrderings(
                    array(
                        'deleted, rand()' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
                    )
                );
            } else {
                // On fait le tri selon des champs configurés
                $orderings = $this->getOrderingsFromSettings($currentRequest);
                $query->setOrderings($orderings);
            }
        }        
    }
    
    public function getOrderingsFromSettings($currentRequest = array()){
        //On determine d'apres le confs si le tri est ascendant ou descendant
        $orderFromSettings = $this->getOrderFromSettings($currentRequest);
		
		// Si on a le bon paramètre dans l'url, le tri est fait en fonction de ce paramètre : orderby
        $order = (!empty($currentRequest['orderby'])) ? $currentRequest['orderby'] : $this->settings['orderBy'];
		
        // Les champs du tri sont récupéré à partir des confs
        $arrayTri = explode(',',$order);
            
        // On met les infos de tri sous la forme d'un tableau pour extbase ($tableau[champ] = ORDER_ASCENDING)
        $orderings = $this->getMultipleOrderings($orderFromSettings,$arrayTri);

        return $orderings;
    }
    
    public function getMultipleOrderings($orderFromSettings,$champTriArray){
        $arrayTri = array();
        foreach($champTriArray as $champTri){
            $arrayTri[$champTri] = $orderFromSettings;
        }
        
        return $arrayTri;
    }
    
    public function getOrderFromSettings($currentRequest){
		if($this->settings['ordre']){
			$order = $this->settings['ordre'];
		}
		if($currentRequest['sorting']){
			$order = $currentRequest['sorting'];
		}
		
		switch ($order) {
			case 'DESC':           
				$orderFromSettings = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break; 
			case 'ASC':
			default:    
				$orderFromSettings = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING; 
			break;
		}
		           
        return $orderFromSettings;
    }
           
	public function getConstraintFromSettings($query){
		$constraint = array();
		
		$filtreArray = $this->settings['filtre'];      

		if(is_array($filtreArray) && !empty($filtreArray)){
			foreach($filtreArray as $filtre => $filtreConf){
                $filtreConf['value'] = $this->convertValueIntoStdWrap($this->settings['filtre'][$filtre]);

                if($filtreConf['value']){ 
                    $constraint = $this->applyFiltre($filtreConf,$query,$constraint);
				}
			}
		}

		return $constraint;
	}
    
    public function convertValueIntoStdWrap($filtreConf){

		$stdWrapValue = $this->cObj->stdWrap($filtreConf['value'], $filtreConf['valueConf']);

        if(!empty($filtreConf['valueConf']) && !empty($stdWrapValue)){   
            $filtreValue = $this->cObj->stdWrap($filtreConf['value'], $filtreConf['valueConf']);
        } else if(!empty($filtreConf['specialValue'])){
			$filtreValue = $this->getSpecialValue($filtreConf['specialValue'],$filtreConf['value']);
        } else {
            $filtreValue = $filtreConf['value'];
        }
						
        return $filtreValue;
    }
	
	public function getSpecialValue($specialValue,$value){
		$field = $specialValue['specialField'];
		$table = $specialValue['specialTable'];
		
		if(is_array($value) && count($value) > 0){
			$where = '1 = 1 AND (';
			foreach($value as $key => $currentValue){
				$where .= $this->cObj->stdWrap($currentValue,$specialValue['specialValue'][$key]);
			}
			$where .= ')';
		} else {
			$where = $this->cObj->stdWrap($value,$specialValue['specialValue']);
		}
		
		$req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($field, $table, $where);
		
		$result = array();
		
		foreach($req as $item){
			$result[] = $item[$field];
		}
		
		return implode('||',$result);
	}
	
	public function getConstraintFromSearch($currentRequest,$constraints,$query){
		$constraint = array();
		$filtreArray = $currentRequest['search'];
					
        if(is_array($filtreArray) && !empty($filtreArray)){   
            foreach($filtreArray as $filtre => $value){   
		        $this->settings['search']['properties'][$filtre]['value'] = $value;

                if($this->settings['search']['properties'][$filtre]['valueConf'] && $value){
                    // if(array_key_exists('_typoScriptNodeValue',$this->settings['search']['properties'][$filtre]['valueConf'])){
                        $tsAvecPoint = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
                        $this->settings['search']['properties'][$filtre]['valueConf'] = $tsAvecPoint['plugin.']['tx_eannuaires.']['settings.']['search.']['properties.'][$filtre.'.']['valueConf.'];
                    // }
                }

                if($this->settings['search']['properties'][$filtre]['value']){
                    $filtreConf = $this->settings['search']['properties'][$filtre];                                                 
                    $filtreConf['value'] = $this->convertValueIntoStdWrap($filtreConf);
                }
				
                if($filtreConf['value'] || ($filtreConf['value'] == 0 && $filtreConf['zeroAllowed'] == 1) ){
                    $constraint = $this->applyFiltre($filtreConf,$query,$constraint);
                }
            }
        }
            
        if($constraint){
            $constraints[] = $query->logicalAnd($constraint);
        }
		return $constraints;
	}


	public function applyFiltre($filtreConf,$query,$constraint){
		$typeReq = $filtreConf['typeRequete'];

		$champFiltre = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$filtreConf['champFiltre']);
        $filtreValue = $filtreConf['value'];
                
		if($typeReq != 'contains'){
			$value = $this->generateValue($typeReq,$filtreValue,$filtreConf);
		}

        
		if($typeReq == 'equals'){  
            if(is_array($champFiltre) && !empty($champFiltre)){
                foreach($champFiltre as $champ){
                    $constraintIntermediaire[] = $query->$typeReq($champ,$value,$filtreConf['caseSensitive']);
                }
                $constraint[] = $query->logicalOr($constraintIntermediaire);
            }
		} else if($typeReq == 'contains'){
            $andOr = ($filtreConf['andOr']) ? $filtreConf['andOr'] : 0;  
            $constraint = $this->generateContainsValue($constraint,$query,$filtreValue,$champFiltre,$andOr);
        } else if($typeReq == 'typeCat'){
			$constraint = $this->generateTypeCatValue($constraint,$query,$value,$filtreConf);
		} else if($typeReq == 'keyword'){
            $constraint = $this->keyWordConstraint($constraint,$query,$value,$filtreConf);
        } else if($typeReq == 'extendKeyword'){
            $constraint = $this->extendKeyWordConstraint($constraint,$query,$value,$filtreConf);
        } else if($typeReq == 'between'){
            $constraint = $this->generateBetweenValue($constraint,$query,$value,$filtreConf,$champFiltre);
		} else {	
            if(is_array($champFiltre) && !empty($champFiltre) && ($champFiltre[0] != '' && count($champFiltre) ==1)){

                foreach($champFiltre as $champ){
                    $constraintIntermediaire[] = $query->$typeReq($champ,$value);
                }

	            $constraint[] = $query->logicalOr($constraintIntermediaire);
            }

		}
        
		return $constraint;
	}
    
    public function extendKeyWordConstraint($constraint,$query,$value,$filtreConf){		
		$constraintFinal = array();
		
		$queryKeyWord = $this->createQuery();

		$queryKeyWord->getQuerySettings()->setRespectStoragePage(FALSE);
		
		$constraintInter = $this->keyWordConstraint(array(),$queryKeyWord,$value,$filtreConf);
		
		$queryKeyWord->matching(
			$queryKeyWord->logicalOr($constraintInter)
		);
		
		$res =  $queryKeyWord->execute()->toArray();
		
        $fieldsArray = explode(',',$filtreConf['champFiltre']);
        
        $andOr = $filtreConf['keyWord'];
        $keyWordInstruction = $filtreConf['keyWordInstruction'];
        
        foreach($fieldsArray as $field){
            $constraintKeyWord[] = $this->searchKeyWord($field,$value,$query,$andOr,$keyWordInstruction);
        }
				
		if(is_array($res) && count($res) > 0){
			$resArray = array();
			foreach($res as $fiche){
				$resArray[] = $query->contains($filtreConf['field'],$fiche->getUid());
			}

			if(count($res) > 1){
				$constraintKeyWord[] = $query->logicalOr($resArray);
			} else {
				$constraintKeyWord[] = $resArray[0];
			}
		}
        
        $constraint[] = $query->logicalOr($constraintKeyWord);
		                
        return $constraint;
    }
    
    public function keyWordConstraint($constraint,$query,$value,$filtreConf){
        $fieldsArray = explode(',',$filtreConf['champFiltre']);
        
        $andOr = $filtreConf['keyWord'];
        $keyWordInstruction = $filtreConf['keyWordInstruction'];
        
        foreach($fieldsArray as $field){
            $constraintKeyWord[] = $this->searchKeyWord($field,$value,$query,$andOr,$keyWordInstruction);
        }
        
        $constraint[] = $query->logicalOr($constraintKeyWord);
                
        return $constraint;
    }
    
    public function searchKeyWord($field,$keyWord,$query,$andOr,$keyWordInstruction = 'standard'){
        $keyWordArray = preg_split('/[ ,]/', $keyWord);
        
        $constraint = array();
        
        foreach($keyWordArray as $key => $word){
            $wordConstraintArray = $this->getKeyWordInstruction($keyWordInstruction, $word, $field, $query, $key);        
            $constraint[] = $query->logicalOr($wordConstraintArray);
        }
                
        if($andOr == 'AND'){
            $constraintKeyWord = $query->logicalAnd($constraint);
        } else{
            $constraintKeyWord = $query->logicalOr($constraint);
        }
        
        return $constraintKeyWord;
    }
	
    public function getKeyWordInstruction($keyWordInstruction, $word, $field, $query, $key)
	{
        $finalInstruction = array();

        switch($keyWordInstruction)
		{
            case 'strict' :                
                $finalInstruction[] = $query->like($field,'% '.trim($word).'%');
                $finalInstruction[] = $query->like($field,'%'.trim($word).' %');
                $finalInstruction[] = $query->like($field, $word);
                break;
            case 'standard' :
            default :
                $finalInstruction[] = $query->like($field, '%'.trim($word).'%');
                break;
		}
        
        return $finalInstruction;
    }

    public function generateTypeCatValue($constraint,$query,$value,$filtreConf){
        $cas = $filtreConf['andOr'];
        $champFiltre = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$filtreConf['champFiltre']);
        
		switch ($cas) {
			case 3:
				$constraint = $this->generateContainsValue($constraint,$query,$value,$champFiltre,'1');
				break;
			case 2:
				$constraint[] = $query->in($champFiltre,explode(',',$value));
				break;
			case 1:
			default:                 
				$constraint = $this->generateContainsValue($constraint,$query,$value,$champFiltre);
				break;
		}
        
        return $constraint;
    }

    public function generateBetweenValue($constraint,$query,$value,$filtreConf,$champFiltre){
        
        $valueArray = explode('<',strval($value));
        $constraintStep = array();
        
        if(is_array($champFiltre) && !empty($champFiltre)){
            foreach ($champFiltre as $champ){
                if (count($valueArray) ==1){
                    $constraintIntermediaire[] = $query->greaterThanOrEqual($champ, (int) $valueArray[0]);
                }else {
                    $constraintStep[] = $query->greaterThanOrEqual($champ, (int) $valueArray[0]);
                    $constraintStep[] = $query->lessThanOrEqual($champ,(int) $valueArray[1]);
                    
                    
                    $constraintIntermediaire[] = $query->logicalAnd($constraintStep);
                }
            }   
            $constraint[] = $query->logicalAnd($constraintIntermediaire);
            
        }
        return $constraint;
    }

    public function generateContainsValue($constraint,$query,$filtreValue,$champFiltre, $equals = '0'){
        $valueArray = (!is_array($filtreValue)) ? explode('||',strval($filtreValue)) : $filtreValue;
        
        $constraintStep = array();
        
        if(is_array($champFiltre) && !empty($champFiltre)){
            foreach ($champFiltre as $champ){
                foreach($valueArray as $value){
                    $constraintStep[] = $query->contains($champ,$value);
                }
                
                if($equals == '1'){
                    $constraintIntermediaire[] = $query->logicalAnd($constraintStep);
                } else {
                    $constraintIntermediaire[] = $query->logicalOr($constraintStep);
                }
            }   
            if($equals == '1'){
                $constraint[] = $query->logicalAnd($constraintIntermediaire);
            } else {
                $constraint[] = $query->logicalOr($constraintIntermediaire);
            }
        }
        
        return $constraint;
    }

	public function generateValue($typeReq,$value,$filtreConf){
        if($filtreConf['recursive']){
            $result = $this->getRecursiveValue($value,$filtreConf);
            $value = $result[1];
        }
        
		switch ($typeReq) {
			case 'in':
                $finalValue = explode('||',$value);
				break;
			case 'like':
				$finalValue = $this->generateLikeValue($value,$filtreConf);
				break;
			default:
				$finalValue = $value;
				break;
		}
        				
		return $finalValue;
	}
    
    public function getRecursiveValue($value,$filtreConf,$select = 0,$res = array(0)){
        $recursive = $filtreConf['recursive'];
        
        if($res[0] < 1){
            $res[1] .= $value;
        }
        
        if($value){
            $req = $this->getChildrenRecords($filtreConf,$value);

            $res[0]++;  
            
            if(is_array($req) && !empty($req) && count($req) > 0){                
                $res = $this->recursiveCallIfResults($req,$res,$recursive,$filtreConf,$value,$select);
            }
        }
        
        return $res;
    }
    
    public function recursiveCallIfResults($req,$res,$recursive,$filtreConf,$value,$select){
        foreach($req as $parent){
            if($select === 1 && $parent[$filtreConf['returnedField']]){
                $compteur = $res[0];
                while($compteur > 0){
                    $parent[$filtreConf['returnedField']] = '&nbsp;&nbsp;&nbsp;'.$parent[$filtreConf['returnedField']];
                    $compteur--;
                }
            }
            
            $res[1] .= ($filtreConf['returnedField']) ? '||'.$parent[$filtreConf['returnedField']] : '||'.$parent['uid'];
                
            if(intval($recursive) > $res[0] && $value){
                $result = $this->getRecursiveValue($parent['uid'],$filtreConf,$select,$res);
                $res[1] = $result[1];
            }
        }

        return $res; 
    }
    
    public function getChildrenRecords($filtreConf,$value){
        $tables = $filtreConf['table'];
        $select = ($filtreConf['selectFields']) ? $filtreConf['selectFields'] : 'uid';
		$orderby = $filtreConf['orderBy'];
		$groupby = $filtreConf['groupBy'];
		
        $where = $filtreConf['parentField'].' IN ('.$value.') ';

        if(!empty($filtreConf['andWhere'])){   
            $where .= ' AND '.$filtreConf['andWhere'];
        }
        
        $tableFinal = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$tables);

		if(!empty($tableFinal)){
            foreach($tableFinal as $table){
                // $where .= \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::enableFields($table);
                $where .= $this->cObj->enableFields($table);
            }
        }
        	
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;	
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$tables,$where,$groupby,$orderby);

        return $req;
    }
	
	public function getFichesUser($uid, $count = 0){
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
		// On cherche les fiches ayant comme feuser associé le feuser passé en parametre.
		$query->matching($query->contains('feuser',$uid));

        // Si, dans le controller,on a spéfifié que l'on voulait le nombre de résultat, en le renvoie au lieu de la liste
        if($count == 1){
    		return $query->count();
        }
                
		return $query->execute();
	}
    
	public function updateTypeFiche($origin){
		switch($origin){
			case 'import':
			break;
			case 'convert':
			break;			
		}
		$this->updateTypeFicheIfConvert();
		$this->updateTypeFicheIfImport();
	}
	
	public function updateTypeFicheIfImport(){
		$typeRepo = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Repository\Conf\TypeannuaireRepository');
		$typeArrayInfo = array();
		
		$listExistingTypes = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('DISTINCT typeelement','tx_eannuaires_domain_model_fiche',' deleted = 0 ');
		
		foreach($listExistingTypes as $uidType){
			$titleType = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('title','tx_eannuaires_domain_model_typeannuaire',' uid = '.$uidType['typeelement']);
			
			if(!empty($titleType)){
				$typeArrayInfo[$uidType['typeelement']] = $titleType[0]['title'];
			}
		}
		
		$this->updateFiche($typeArrayInfo);
	}
	
	public function updateTypeFicheIfConvert(){
		$confExtTplAnnuaires = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
		
		$typeArray = explode(',',$confExtTplAnnuaires['types']);
		$typeArrayInfo = array();
		
		foreach($typeArray as $typeOld){
			$typeOldArray = explode('-',$typeOld);
			$typeArrayInfo[$typeOldArray[0]] = $typeOldArray[1];
		}
		
		$this->updateFiche($typeArrayInfo);
	}
	
	public function updateFiche($typeArrayInfo){
		if(is_array($typeArrayInfo) && !empty($typeArrayInfo)){
			foreach($typeArrayInfo as $idOld => $nameOld){
				$idToUpdate = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid','tx_eannuaires_domain_model_typeannuaire','title LIKE "%'.$nameOld.'%" AND deleted = 0 AND hidden = 0 ');
				
				// On met à jour les fiches
				$arrayToUpdate = array(
					'typeelement' => $idToUpdate[0]['uid']
				);
				$where = 'typeelement = '.$idOld;
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_eannuaires_domain_model_fiche',$where,$arrayToUpdate);

				
				// On met à jour les pages (conf pour le type)
				$tsConfigToAdd = chr(10).'TCAdefaults.tx_eannuaires_domain_model_fiche.typeelement = '.$idToUpdate[0]['uid'];
				// $tsConfigToAdd = $GLOBALS['TYPO3_DB']->fullQuoteStr($tsConfigToAdd, "");
				
				$arrayToUpdate2 = array(
					'typeannuaire' => $idToUpdate[0]['uid'],
					'TSconfig' => 'CONCAT(TSconfig,'.$GLOBALS['TYPO3_DB']->fullQuoteStr($tsConfigToAdd, 'table').')'
				);
				$where2 = 'typeannuaire = '.$GLOBALS['TYPO3_DB']->fullQuoteStr($idOld, 'pages');
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages',$where2,$arrayToUpdate2,'TSconfig');
							
			}
		}
	}
}
