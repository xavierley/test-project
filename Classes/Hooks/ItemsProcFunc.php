<?php
namespace Emagineurs\EAnnuaires\Hooks;
/***************************************************************
*  Copyright notice
*
*  (c) 2010 E Beranger <eberanger@e-magineurs.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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

use \TYPO3\CMS\Backend\Form\FormEngine;

/**
 * Userfunc to render alternative label for media elements
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ItemsProcFunc  {
	
	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutListe(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'liste');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutDetail(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'detail');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutCatmenu(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'catmenu');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutSearch(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'search');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutResult(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'result');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutManage(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'manage');
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_templateLayoutEdit(array &$config,  $parentObject) {
		$this->handleTemplateLayouts($config,  $parentObject, 'edit');
	}
    
	
	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @param string $action 
	 * @return void
	 */
	public function handleTemplateLayouts(array $config,  $parentObject, $action) {
        $row = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $config['row']['uid']);
		$confTsConfig = array();
		$confExtLocalConf = array();
		
		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			
			if (isset($pagesTsConfig['tx_eannuaires.']['templateLayouts.']) && is_array($pagesTsConfig['tx_eannuaires.']['templateLayouts.'][$action.'.'])) {
				$confTsConfig = $pagesTsConfig['tx_eannuaires.']['templateLayouts.'][$action.'.'];
			}
			if(count($GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout'][$action]) > 0){
				$confExtLocalConf = $GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout'][$action];
			}
			
			$confArray = $confTsConfig+$confExtLocalConf;
			
			if(count($confArray) > 0){
				// //Add every item
				foreach ($confArray as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
				
				return $config;
			}	
		}
	}
	
	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_selecttype(array &$config,  $parentObject) {        
        $confExtTpl = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);

		// On instancie un sys_registry
		$registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');

		// On récupère la conf depuis la table des registres
		$confAnnuaires = $registry->get('tx_eannuaires','confAnnuaires');

		if(is_array($confAnnuaires) && !empty($confAnnuaires)){
			$confExtTplAnnuaires['types'] = $confAnnuaires;
		}
        
        $typeList = $confExtTpl['types'];
        
        $typeArray = explode(',',$typeList);
        
        if(is_array($typeArray) && !empty($typeArray) && count($typeArray) > 0){            
            foreach($typeArray as $typeItem){
                $typeItemArray = explode('-',$typeItem);
                
                $type = array(
                    $typeItemArray[1],
                    $typeItemArray[0]
                );
                
                array_push($config['items'], $type);
            }
        }        
	}
	
	/**
	 * Itemsproc function to extend the selection of types of etablissments in tca
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemEtablissements(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemEtablissements.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemEtablissements.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of handicap in tca
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemHandicap(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemHandicap.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemHandicap.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}	
    
	/**
	 * Itemsproc function to extend the selection of types of contrat in tca
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
		public function user_itemContrat(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);       
			if (isset($pagesTsConfig['tx_eannuaires.']['itemContrat.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemContrat.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);					
					array_push($config['items'], $additionalLayout);
				}
			}
		}
		// die;
	}	

	/**
	 * Itemsproc function to extend the selection of types of epci in tca
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemEPCI(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemEPCI.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemEPCI.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}	

	/**
	 * Itemsproc function to extend the selection of types of natural region in fiche.communes
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemRegionNat(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemRegionNat.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemRegionNat.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of anrrondissement in tca fiche.communes
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemArrondissement(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemArrondissement.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemArrondissement.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of Canton in fiche.commune and mandat
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemCanton(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemCanton.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemCanton.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of Canton in fiche.commune and mandat
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemCircLegislative(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemCircLegislative.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemCircLegislative.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of Parti politique in fiche of Elus
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemPartiPolitique(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemPartiPolitique.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemPartiPolitique.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of Mandats in fiche of Elus
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemTypeMandat(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);       			
			if (isset($pagesTsConfig['tx_eannuaires.']['itemTypeMandat.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemTypeMandat.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
				//exec_SELECTgetRows ($select_fields, $from_table, $where_clause, $groupBy= '', $orderBy= '', $limit= '', $uidIndexField= '')
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of Circonscription  in mandat
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemCirconscription(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemCirconscription.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemCirconscription.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}


	/**
	 * Itemsproc function to extend the selection of types of functions in mandat
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemFonctions(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemFonctions.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemFonctions.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}

	/**
	 * Itemsproc function to extend the selection of types of strucutre in rij and scene culturel
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
	public function user_itemTypeStructure(array &$config,  $parentObject) {
        $row = $config['row'];

		// Add tsconfig values
		if (is_numeric($row['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($row['pid']);            
			if (isset($pagesTsConfig['tx_eannuaires.']['itemTypeStructure.'])) {
				// //Add every item
				foreach ($pagesTsConfig['tx_eannuaires.']['itemTypeStructure.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}
	
	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
    public function user_addSortingFields(array &$config,  $parentObject) {  
        $confExtTpl = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
        
        $fieldList = $confExtTpl['sortingFields'];
        
        $fieldArray = explode(',',$fieldList);
        
        if(is_array($fieldArray) && !empty($fieldArray) && count($fieldArray) > 0){            
            foreach($fieldArray as $fieldItem){    
                $field[0] = $fieldItem;
                $field[1] = $fieldItem;
                array_push($config['items'], $field);
            }
        }    
    }
    
	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param FormEngine $parentObject parent object
	 * @return void
	 */
    public function user_addTypeMedia(array &$config,  $parentObject) {  
        $confExtTpl = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
        
        $fieldList = $confExtTpl['typedisplay'];
        
        $fieldArray = explode(',',$fieldList);
        
        if(is_array($fieldArray) && !empty($fieldArray) && count($fieldArray) > 0){            
            foreach($fieldArray as $fieldItem){    
                $field[0] = $fieldItem;
                $field[1] = $fieldItem;
                array_push($config['items'], $field);
            }
        }    
    }

    public function user_labelMandats(array &$parameters,  $parentObject) {  
		$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($parameters['row']['pid']);     
		
		$typeMandat = $parameters['row']['typemandat'];
		
		if(!empty($typeMandat)){
			if(is_array($typeMandat)){
				$tsconfigMandat = $pagesTsConfig['tx_eannuaires.']['itemTypeMandat.'][reset($typeMandat)];
			} else {
				$tsconfigMandat = $pagesTsConfig['tx_eannuaires.']['itemTypeMandat.'][$typeMandat];
			}
		} else {
			$tsconfigMandat = $pagesTsConfig['tx_eannuaires.']['labelMandat'] ? $pagesTsConfig['tx_eannuaires.']['labelMandat'] : '';
		}
		
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($parameters['row'],'TEST $parameters');
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($pagesTsConfig['tx_eannuaires.'],'TEST $pagesTsConfig');
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($typeMandat,'TEST $typeMandat');
		
		$newTitle = !empty($tsconfigMandat) ? $tsconfigMandat : 'mandat non défini' ;
		
		$parameters['title'] = $newTitle;
    }

}	


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/e_annuaires/Classes/Hooks/ItemsProcFunc.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/e_annuaires/Classes/Hooks/ItemsProcFunc.php']);
}
?>