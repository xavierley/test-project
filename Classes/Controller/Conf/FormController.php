<?php
namespace Emagineurs\EAnnuaires\Controller\Conf;

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
 
 use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
 use \TYPO3\CMS\Core\Service;
 
/**
 *
 *
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * markerService
	 *
	 * @var \TYPO3\CMS\Core\Service\MarkerBasedTemplateService
	 * @inject
	 */
	protected $markerService;
	
    /**
	 * markerService
	 *
	 * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 * @inject
	 */
	protected $reflection;

	/**
	 * initialize create action
	 * allow creation of submodel company
	 */
	public function initializeAction(){ 
		// On instancie un sys_registry
		// $this->registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');
		
		// On génère le tableau avec toutes les infos sur le model
		$this->modelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Fiche');
	}
			
	/**
	 * action index	
	 *
	 * @return void
	 */
	public function manageFormAction(){
		$listType = $this->typeannuaireRepository->findAll();
		$this->view->assign('types',$listType);
	}
	
	public function generateFormTemplateAction(){
		$currentRequest = $this->request->getArguments();
		$fields = $currentRequest['arguments']['arrayFields'];
		
		// La présence ou on de RTE dans le formulaire change les attributs de la balise form, on l'initialise donc a false
		// et on l'assigne après le parsing des champs (dans lequel se fera le changement de valeur si nécéssaire).
		$this->rte = false;

		$fieldsContent = '';
		
		if(is_array($fields) && count($fields) > 0){
			foreach($fields as $field){
				$tcaConfig = $GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$field]['config'];
				
				try{
					$templateField = $this->getTemplateField($tcaConfig,$field);
					$fieldsContent .= $templateField;
				} catch(\Exception $e){
					echo 'ECHEC de la génération du champ '.$field.' pour le template : '.$e->getMessage();
				}
			}
		}

		if($this->rte === TRUE){
			$formPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:e_annuaires/Resources/Private/Templates/Conf/Form/FormRte.html');
		} else {
			$formPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:e_annuaires/Resources/Private/Templates/Conf/Form/Form.html');
		}
		$formTemplate = file_get_contents($formPath);

		$contentObjectRenderer = $this->objectManager->get('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$content = $contentObjectRenderer->wrap($fieldsContent,$formTemplate,'||');
		
		// On met les données dans les sessions car le dispatcher ne semble pas permettre un simple return.
		$arrayToReturn = array(
			'generateFormTemplate' => $content
		);
		$GLOBALS['BE_USER']->setAndSaveSessionData('tx_eannuaires', $arrayToReturn);
	}
	
	public function getTemplateField($tcaConfig,$field){
		$type = $tcaConfig['type'];
		$handler = 'get'.ucFirst($type).'Template';
		
		if(in_array($handler,get_class_methods($this))){
			// Raté pour l'aspect Fluid :) 
			$view = $this->getFluidTemplate($type);

			// Dans les handlers on fait les assignations en fonction des conf TCA et model
			$template = $this->$handler($tcaConfig,$view,$field);
		
			return $template;
		} else {
			throw new \Exception('No handler is defined For this TCA type in class '.__CLASS__); 
		}
	}
	
	public function getFluidTemplate($type){
		$mainTemplate = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:e_annuaires/Resources/Private/Templates/Conf/Form/'.ucfirst($type).'.html');
		$contentTemplate = file_get_contents($mainTemplate);
		
		$template = $this->markerService->getSubpart($contentTemplate, '###SUBPART_CURRENT_TCATYPE###');
		
		return $template;
	}
	
	public function getSelectTemplate($tcaConfig,$view,$field){
		$contentSubpart = '';
		$template = '';
		$currentSubpart = '';
		
		$modelConfig = $this->modelSchema->getProperties();
		$modelConfigField = $modelConfig[$field];
		
		if($tcaConfig['renderType'] == 'selectSingle'){
			if($modelConfigField['type'] == 'TYPO3\CMS\Extbase\Persistence\ObjectStorage'){
				$currentSubpart = 'single_storage';
			} else {
				$currentSubpart = 'single_raw';
			}
		} else {
			$currentSubpart = 'multiple_raw';
		}
		
		$markerArray = array(
			'###FIELD###' => $field,
			'###UCF_FIELD###' => ucfirst($field)
		);
				
		$template = $this->markerService->getSubpart($view, '###SUBPART_CURRENT_TCATYPE_'.strtoupper($currentSubpart).'###');
		$contentSubpart = $this->markerService->substituteMarkerArray($template,$markerArray);
		
		return $contentSubpart;
	}
		
	public function getCheckTemplate($tcaConfig,$view,$field){
		$contentSubpart = '';
		$template = '';
		
		$currentSubpart = 'radio';
		
		$markerArray = array(
			'###FIELD###' => $field
		);
		
		$template = $this->markerService->getSubpart($view, '###SUBPART_CURRENT_TCATYPE_'.strtoupper($currentSubpart).'###');
		$contentSubpart = $this->markerService->substituteMarkerArray($template,$markerArray);
		
		return $contentSubpart;
	}
	
	public function getInputTemplate($tcaConfig,$view,$field){
		$contentSubpart = '';
		$template = '';
				
		if(!empty($tcaConfig['eval'])){
			if(strpos($tcaConfig['eval'],'date') !== FALSE){
				$date = TRUE;
			}
		}
		
		$markerArray = array(
			'###FIELD###' => $field
		);
			
		if($date === true){
			$currentSubpart = 'date';
		} else {
			$currentSubpart = 'simple';
		}
		
		$template = $this->markerService->getSubpart($view, '###SUBPART_CURRENT_TCATYPE_'.strtoupper($currentSubpart).'###');
		$contentSubpart = $this->markerService->substituteMarkerArray($template,$markerArray);
		
		return $contentSubpart;
	}
	
	public function getTextTemplate($tcaConfig,$view,$field){
		$contentSubpart = '';
		$template = '';
		
		if(!empty($tcaConfig['wizards'])){
			if(array_key_exists('RTE',$tcaConfig['wizards'])){
				$this->rte = TRUE;
			}
		}
		
		$markerArray = array(
			'###FIELD###' => $field
		);
			
		if($this->rte === true){
			// Actuellement on se sait pas faire fonctionner le RTE en front donc on se contente d'un textarea
			// $currentSubpart = 'withrte';
			$currentSubpart = 'withoutrte';
		} else {
			$currentSubpart = 'withoutrte';
		}
		
		$template = $this->markerService->getSubpart($view, '###SUBPART_CURRENT_TCATYPE_'.strtoupper($currentSubpart).'###');
		$contentSubpart = $this->markerService->substituteMarkerArray($template,$markerArray);
		
		return $contentSubpart;
	}
	
	public function getGroupTemplate($tcaConfig,$view,$field){
		$contentSubpart = '';
		$template = '';
		return $contentSubpart;
	}
	
	public function getInlineTemplate($tcaConfig,$view,$field){		
		$modelConfig = $this->modelSchema->getProperties();
		$modelConfigField = $modelConfig[$field];
		
		if($modelConfigField['elementType'] == \Emagineurs\EAnnuaires\Domain\Model\Filereference::class){
			$currentSubpart = 'image';
		} else {
			$currentSubpart = 'inline';
		}
		
		$markerArray = array(
			'###FIELD###' => $field,
			'###UCF_FIELD###' => ucfirst($field)
		);
				
		$template = $this->markerService->getSubpart($view, '###SUBPART_CURRENT_TCATYPE_'.strtoupper($currentSubpart).'###');
		$contentSubpart = $this->markerService->substituteMarkerArray($template,$markerArray);
		
		return $contentSubpart;
	}
	
}
?>