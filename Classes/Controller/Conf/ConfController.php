<?php
namespace Emagineurs\EAnnuaires\Controller\Conf;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Xavier Ley <xley@e-magineurs.com>
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
class ConfController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
	 * typeannuaireRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\Conf\TypeannuaireRepository
	 * @inject
	 */
	protected $typeannuaireRepository;

    /**
	 * champBddRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\Conf\ChampbddRepository
	 * @inject
	 */
	protected $champBddRepository;

    /**
	 * ficheRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\FicheRepository
	 * @inject
	 */
	protected $ficheRepository;

	/**
	 * initialize create action
	 * allow creation of submodel company
	 */
	public function initializeAction(){ 
		// On instancie un sys_registry
		$this->registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');
		
		$this->listeChampsBdd = $this->getListeChampsBdd();
	}
			
	/**
	 * action index	
	 *
	 * @return void
	 */
	public function indexAction(){
		// $typeannuaireRepository = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Repository\Conf\TypeannuaireRepository');
		$listTypes = $this->typeannuaireRepository->findAll();
		
		$locallangPath = $this->registry->get('tx_eannuaires','locallangPath');
		
		$this->view->assign('locallangPath',$locallangPath);
		$this->view->assign('listTypes',$listTypes);
		$this->view->assign('ListeChampBdd',$this->listeChampsBdd);
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->view);
		
		// \t3lib_utility_Debug::debug(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']),'TEST');
	}
	
	
	/**
	 * action createType	
	 *
	 * @return void
	 */
	public function createTypeAction(){		
		$currentRequest = $this->request->getArguments();
		
		// On crée le nouveau type
		$newType = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire');
		$newType->setTitle($currentRequest['newType']); 
		
		$this->typeannuaireRepository->add($newType);
		
		// On crée, par défaut, un champ titre pour le type que l'on vient de créer
		$newField = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Champbdd');
		$newField->setTitle('title'); 
		$newField->setSorting(0); 
		$newField->setTypeannuaire($newType); 
		
		$this->champBddRepository->add($newField);
		
		// On rend effectifs les ajouts définis au dessus
		$this->typeannuaireRepository->publicPersistAll();
		$this->champBddRepository->publicPersistAll();

		// prépare les rendu frontend
		$this->view->assign('type',$newType);
		$contentConf = $this->getContentConf($newType);
		
		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		$arrayToReturn = array(
			'contentType' => $this->view->render(),
			'contentConf' => $contentConf,
			'idType' => $newType->getUid()
		);
		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
	}
	
	public function getContentConf($type){		
		$tmpView = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');
		$mainTemplate = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:e_annuaires/Resources/Private/Partials/Conf/Type.html');
		$tmpView->setTemplatePathAndFilename($mainTemplate);
		
		$tmpView->assign('type',$type);
		$tmpView->assign('ListeChampBdd',$this->listeChampsBdd);
		
		return $tmpView->render();
	}
	
	/**
	 * action deleteType	
	 *
	 * @return void
	 */
	public function deleteTypeAction(){
		$currentRequest = $this->request->getArguments();
		
		$typeToDeleteInfoArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('-',$currentRequest['typeToDelete']); 
		$typeToDeleteUid = $typeToDeleteInfoArray[1];
		
		$typeToDelete = $this->typeannuaireRepository->findByUid($typeToDeleteUid);
		
		$this->typeannuaireRepository->remove($typeToDelete);
		
		$this->typeannuaireRepository->publicPersistAll();

		// On vérifie que la suppression à bien fonctionné
		$typeToDelete = $this->typeannuaireRepository->findByUid($typeToDeleteUid);

		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		if(!$typeToDelete){
			$arrayToReturn = array(
				'deleteSuccess' => true
			);
			$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
		}
	}
	
	/**
	 * action generateDetailConf	
	 *
	 * @return void
	 */
	public function generateDetailConfAction(){
		$currentRequest = $this->request->getArguments();
		
		$source = $currentRequest['source'] ? $currentRequest['source'] : 'tca' ; 
		$idTypeInfoArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('-',$currentRequest['idType']); 
		$idType = $idTypeInfoArray[1];
						
		if(trim($idType) != ''){	
			$selectedFields = $this->champBddRepository->selectConfType($idType);
			$this->view->assign('selectedFields',$selectedFields);
			$this->view->assign('source',$source);
	
			$arrayToReturn = array(
				'generateDetailConf' => $this->view->render()
			);
			
			// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
			$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
		} else {
			return 'Aucun type d\'annuaire n\a été selectionné';
		}
	}
	
	/**
	 * action addNewField	
	 *
	 * @return void
	 */
	public function addNewFieldAction(){
		$currentRequest = $this->request->getArguments();
				
		// On instancie un nouveau champ
		$newField = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Champbdd');
		
		// On lui attribue un titre, un type et un sorting
		$newField->setTitle($currentRequest['newFieldName'][0]); 
		$newField->setSorting($currentRequest['sorting']); 
		
		$type = $this->typeannuaireRepository->findByUid($currentRequest['newFieldType']);
		$newField->setTypeannuaire($type); 
				
		// On fait l'ajout
		$this->champBddRepository->add($newField);
		
		// On rend effetifs les ajouts définis au dessus
		$this->champBddRepository->publicPersistAll();
		
		$newFieldCheck = $this->champBddRepository->findByUid($newField->getUid());
				
		$this->view->assign('newField',array($newField));
		$content = ($newFieldCheck !== null) ? $this->view->render() : '' ;
				
		$arrayToReturn = array(
			'addNewField' => $content
		);
			
		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
	}
	
	/**
	 * action increaseSorting	
	 *
	 * Cette fonction va permettre d'inverser les sorting
	 *
	 * @return void
	 */
	public function changeSortingAction(){
		$currentRequest = $this->request->getArguments();
		
		$lowSorting = $currentRequest['lowSorting'];
		$highSorting = $currentRequest['highSorting'];
		
		// On récupère les objets correspondants aux champs dont l'on souhaite échanger les places
		$fieldWithIncreasingSorting = $this->champBddRepository->findByUid($lowSorting);
		$fieldWithDecreasingSorting = $this->champBddRepository->findByUid($highSorting);
	
		// On stocke les sorting dans des variables
		$highestSorting = $fieldWithDecreasingSorting->getSorting(); 
		$lowestSorting = $fieldWithIncreasingSorting->getSorting(); 
					
		// On attribue le sorting de chaque item à l'autre
		$fieldWithIncreasingSorting->setSorting($highestSorting); 
		$fieldWithDecreasingSorting->setSorting($lowestSorting); 
			
		//On fait l'update	
		$this->champBddRepository->update($fieldWithIncreasingSorting);
		$this->champBddRepository->update($fieldWithDecreasingSorting);
			
			
		// On rend effetifs les modifications définis au dessus
		$this->champBddRepository->publicPersistAll();
	}
	
	/**
	 * action deleteField	
	 *
	 * @return void
	 */
	public function deleteFieldAction(){
		$currentRequest = $this->request->getArguments();
		
		$fieldToDelete = $this->champBddRepository->findByUid($currentRequest['fieldToDelete']);
		$fieldsToUpdate = $this->champBddRepository->findByTypeannuaire($fieldToDelete->getTypeannuaire())->toArray();
		
		$this->champBddRepository->remove($fieldToDelete);
		
		if(is_array($fieldsToUpdate) && !empty($fieldsToUpdate)){
			foreach($fieldsToUpdate as $field) {
				$newSorting = (intval($field->getSorting())-1) == 0 ? -1 : intval($field->getSorting())-1 ;  
				$field->setSorting($newSorting);
				$this->champBddRepository->update($field);
			}
		}
		
		$this->champBddRepository->publicPersistAll();

		// On vérifie que la suppression à bien fonctionné
		$fieldToDeleteCheck = $this->champBddRepository->findByUid($currentRequest['fieldToDelete']);

		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		if(!$fieldToDeleteCheck){
			$arrayToReturn = array(
				'fieldToDelete' => true
			);
			$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
		}
	}
	
	/**
	 * action updateLabelTab	
	 *
	 * @return void
	 */
	public function updateLabelTabAction(){
		$currentRequest = $this->request->getArguments();
				
		$newArrayUpdate = array();
		foreach($currentRequest['updateArray'] as $inputField => $inputVal){
			$infoInput = explode('-',$inputField);
			
			if(!$this->currentField[$infoInput[1]]){
				$this->currentField[$infoInput[1]] = $this->champBddRepository->findByUid($infoInput[1]);
			}
			
			if($this->currentField{$infoInput[1]}){
				if($infoInput[0] == 'newLabel'){
					$this->currentField[$infoInput[1]]->setLabel($inputVal);
				} else if($infoInput[0] == 'newTab'){
					$this->currentField[$infoInput[1]]->setTablabel($inputVal);
				}
			}
			
			$this->champBddRepository->update($this->currentField[$infoInput[1]]);
		}

		$this->champBddRepository->publicPersistAll();
		
		$valid = true;
		
		foreach($this->currentField as $item){
			if(
				$item->getLabel() != $currentRequest['updateArray']['newLabel-'.$item->getUid()]
				|| $item->getTablabel() != $currentRequest['updateArray']['newTab-'.$item->getUid()]
			){
				$valid = false;
			}
		}
		
		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		$arrayToReturn = array(
			'updateLabelTab' => ($valid === true) ? 'Les labels ont bien été sauvegardé.' : 'La sauvegarde des labels a echoué'
		);
		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
	}
	
	/**
	 * action getTcaConf	
	 *
	 * @return void
	 */
	public function getTcaConfAction(){
		$currentRequest = $this->request->getArguments();
		
		$conf = $GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$currentRequest['fieldName']]['config'];
		
		$this->view->assign('conf',$conf);
		
		// On récupère le résultat du debug sous forme d'un html
		ob_start();
		
		// echo '<h4 class="titreConf">'.$currentRequest['fieldName'].'</h4>';
		// echo '<pre>';
		// var_dump($conf);
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($conf,$currentRequest['fieldName']);
		// echo '</pre>';
		
		$content = ob_get_contents();
		
		ob_end_clean();
		
		// On retourne les valeurs via les sessions
		$arrayToReturn = array(
			'getTcaConf' => $content
		);
		
		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
	}
	
	/**
	 * action generateConf
	 *
	 * @return void
	 */
	public function generateConfAction(){
		$confArray= array();
		
		$listType = $this->typeannuaireRepository->findAll()->toArray();
		
		if(is_array($listType) && !empty($listType)){
			foreach($listType as $type){
				$confArray[$type->getUid()] = $this->generateConfArray($type->getUid());
			} 
		}
		
		// On stocke la conf dans la table des registres
		$registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');		
		$registry->set('tx_eannuaires','confAnnuaires',$confArray);
		
		// On retourne les valeurs via les sessions
		if(!empty($confArray)){
			$arrayToReturn = array(
				'generateConf' => true
			);
		
			$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
		}
	}
	
	public function generateConfArray($type){
		if(intval($type) > 0){
			$confTypeArray = array();
			
			$listChampType = $this->champBddRepository->selectConfType($type)->toArray();
			
			foreach($listChampType as $champ){
				$confTypeArray[$champ->getTitle().'.'] = array(
					'value' => $champ->getTitle(),
					'sorting' => $champ->getSorting(),
					'label' => $champ->getLabel(),
					'newTab' => $champ->getTablabel()
				);
			}
			
			return $confTypeArray;
		}
	}
	
	/**
	 * action export
	 *
	 * @return void
	 */
	public function exportAction(){
		$currentRequest = $this->request->getArguments();

		if(!empty($currentRequest['exportConf'])){
			$confAnnuaires = $this->registry->get('tx_eannuaires','confAnnuaires');
			
			foreach($confAnnuaires as $type => $conf){
				$titleType = $this->typeannuaireRepository->findByUid($type);
				
				if($titleType != null){
					$confAnnuaires[$type]['titleType'] = $titleType->getTitle();
				}
			}
			$xmlExport =  \TYPO3\CMS\Core\Utility\GeneralUtility::array2xml_cs($confAnnuaires);
			
			$nomFichier = 'exportConf_'.date('d-m-Y-H-i-s').'.xml';
			
			header('Content-type: text/xml');
			header('Content-Disposition: attachment; filename="'.$nomFichier.'"');

			echo $xmlExport;	
			die;
		}

		$this->view->assign('test','Je suis dans l\'action export de mon module');
	}
		
	/**
	 * action import
	 *
	 * @return void
	 */
	public function importAction(){
		$currentRequest = $this->request->getArguments();
		if(!empty($currentRequest['confToImport'])){
			$importFile = file_get_contents($currentRequest['confToImport']['tmp_name']);
			
			$confArray = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($importFile);
			
			$this->champBddRepository->removeAll();
			$this->typeannuaireRepository->removeAll();
			$this->typeannuaireRepository->publicPersistAll();
			$this->champBddRepository->publicPersistAll();
			
			$confArrayToAdd = array();
			foreach($confArray as $idType => $confType){
				$newType = $this->typeannuaireRepository->findAllByUid($idType);
				
				if(!empty($newType)){
					$newType->setDeleted('0');
					$this->typeannuaireRepository->update($newType);
				} else {
					$newType = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire');
					$newType->setTitle($confType['titleType']);
					$this->typeannuaireRepository->add($newType);
				}
				
				$this->typeannuaireRepository->publicPersistAll();
				
				$confArrayToAdd[$newType->getUid()] = $confType;
			}
			
			$confArray = $confArrayToAdd;
						
			$this->generateNewConf($confArray);
			
			$this->updateTypeFiche('import');
			$confAnnuaires = $this->registry->set('tx_eannuaires','confAnnuaires',$confArray);
			
			$this->view->assign('message','Import terminé');
		}
	}
	
	/**
	 * action convert	
	 *
	 * @return void
	 */
	public function convertAction(){
		$currentRequest = $this->request->getArguments();
		
		if(!empty($currentRequest['generateConfFromLocalconf'])){
			$this->convertOldConf();
			$this->view->assign('message','La conf à bien été générer');
		}
	}
	
	public function convertOldConf(){		
		/*
		*/
		$confExtTplAnnuaires = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);

		// On récupère les données de l'ancinne conf
		$defaultLocallangPath = 'EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf';
		$oldTypes = $confExtTplAnnuaires['types'];
		$oldConf = $confExtTplAnnuaires['confType'];
		
		// On met en base de données les données provenant de l'ancienne conf	
		$this->champBddRepository->removeAll();
		$this->typeannuaireRepository->removeAll();
		$this->typeannuaireRepository->publicPersistAll();
		$this->champBddRepository->publicPersistAll();
			
		$conf = $this->registry->set('tx_eannuaires','locallangPath',$defaultLocallangPath);
		$this->generateTypes($oldTypes);
		$this->generateNewConf($oldConf);
		
		// On attibue le nouveau type correspondant aux fiches existante
		$this->updateTypeFiche('convert');
		
		// On stocke la conf récupere telle quelle du localconfiguration
		$confAnnuaires = $this->registry->set('tx_eannuaires','confAnnuaires',$oldConf);
	}
	
	public function generateTypes($oldTypes){	
		$oldTypesArray = explode(',',$oldTypes);
		
		foreach($oldTypesArray as $typeConfArray){
			$typeArray = explode('-',$typeConfArray);
			$arrayTypes[$typeArray[0]] = $typeArray[1];
		}
	
		$this->oldToNewTypeUid = array();
	
		foreach($arrayTypes as $oldUid => $type){
			// On instancie un object de type conf
			$newType = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire');
			
			// On set les valeurs à partir de celles provenant de l'ancienne conf 
			$newType->setTitle($type); 
			
			$this->typeannuaireRepository->add($newType);
			
			// On rend effetifs les ajouts définis au dessus
			$this->typeannuaireRepository->publicPersistAll();
			
			// On stocke dans un tableau les equivalence entre anciens et nouveaux uid afin de pouvoir les utiliser dans la création des champs, fonction generateNewConf()
			$this->oldToNewTypeUid[$oldUid] = $newType->getUid();
		}
	}
	
	public function generateNewConf($oldConf){	
		if(is_array($oldConf) && !empty($oldConf)){
			foreach($oldConf as $type => $champArray){						
				$idType = ($this->oldToNewTypeUid[$type]) ? $this->oldToNewTypeUid[$type] : $type;
				$typeAnnuaire = $this->typeannuaireRepository->findAllByUid($idType);
				
				// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($idType,'TEST $idType');
				// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($typeAnnuaire,'TEST $typeAnnuaire');
				
				foreach($champArray as $conf){
					if(!empty($conf['value'])){
						// On instancie un object de type conf
						$newChampConf = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Conf\Champbdd');
					
						// On set les valeurs à partir de celles provenant de l'ancienne conf 
						$newChampConf->setTitle($conf['value']);
						$newChampConf->setSorting($conf['sorting']);
						$newChampConf->setLabel($conf['label']);
						$newChampConf->setTablabel($conf['newTab']);
						
						$newChampConf->setTypeannuaire($typeAnnuaire);						
																
						// On fait l'ajout
						$this->champBddRepository->add($newChampConf);
						
						// On rend effetifs les ajouts définis au dessus
						$this->champBddRepository->publicPersistAll();
					}
				}
			}
		}		
	}
	
	public function updateTypeFiche($origin){
		$this->ficheRepository->updateTypeFiche($origin);
	}
	
	public function getListeChampsBdd(){
        // On récupère la liste des champs de la table des fiches annuaires
        $fieldListArray = array(); 
        $fieldListArrayBegin =  $GLOBALS['TYPO3_DB']->admin_get_fields('tx_eannuaires_domain_model_fiche'); 
        
        // On exclut les tables dont on sait qu'elle ne seront pas utilsé dans le TCA
        $excludeFields = array(
            'uid',
            'pid',
            'cruser_id',
            'deleted',
            't3ver_oid',
            't3ver_id',
            't3ver_wsid',
            't3ver_label',
            't3ver_state',
            't3ver_stage',
            't3ver_count',
            't3ver_tstamp',
            't3ver_move_id',
            't3_origuid',
            'l10n_parent',
            'l10n_diffsource',
            'typeelement'
        );
        
        foreach($fieldListArrayBegin as $field => $value){
            if(!in_array($field,$excludeFields)){
                $fieldListArray[$field]['title'] = $field;
                $fieldListArray[$field]['config'] = $GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$field]['config'];
            }
        }
		
		sort($fieldListArray);
		
		return $fieldListArray;
	}
	
}
?>