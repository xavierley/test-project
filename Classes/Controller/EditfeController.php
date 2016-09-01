<?php
namespace Emagineurs\EAnnuaires\Controller;
 
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 xley <xley@e-magineurs.com>
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
 
/**
 *
 *
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EditfeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
	 * ficheRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\FicheRepository
	 * @inject
	 */
	protected $ficheRepository;

    /**
	 * champBddRepository
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Repository\Conf\ChampbddRepository
	 * @inject
	 */
	protected $champBddRepository;

	/**
	 * SignalSlot Dispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 * @inject
	 */
	protected $signalSlotDispatcher;
	
    /**
	 * reflectionService
	 *
	 * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
	 * @inject
	 */
	protected $reflection;
	
	/**
	 * initialize create action
	 * allow creation of submodel company
	 */
	public  function initializeAction ()  { 
        $this->TSinPlugin();
        $this->currentUser = $GLOBALS['TSFE']->fe_user->user;
		
		// On génère le tableau avec toutes les infos sur le model
		$this->ficheModelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Fiche');
		$this->lienModelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Lien');
		$this->mandatModelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Mandat');
		$this->documentsModelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Documents');
		$this->imageModelSchema = $this->reflection->getClassSchema('Emagineurs\EAnnuaires\Domain\Model\Image');
        
		// On instancie ici le generalUtility de e_annuaires
		$this->utility = $this->objectManager->get(\Emagineurs\EAnnuaires\Utility\GeneralUtility::class);
		
        $this->useTsIfFlexIsEmpty();
	}
    
    public function useTsIfFlexIsEmpty(){
        $confTsComplete = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
           
        $confTSWithDots = $confTsComplete['plugin.']['tx_eannuaires.']['settings.'];
        
        $confTS = \Emagineurs\EAnnuaires\Utility\GeneralUtility::removeDotsInConf($confTSWithDots);
        
        foreach($confTS as $conf => $value){
            if(!array_key_exists($conf,$this->settings) || empty($this->settings[$conf])){
                if(trim($value) != '' && !empty($value)){  
                    $this->settings[$conf] = $value;
                } 
            }
        }
    }
    
    public function TSinPlugin(){
        $flexformTyposcript = $this->settings['myTS'];

        if ($flexformTyposcript) {
            $tsparser = $this->objectManager->get('TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser');

            // Parse the new Typoscript
            $tsparser->parse($flexformTyposcript);

            // On enleve les "points" dans les clés du tableau
            $tsparser->setup = $this->removeDotsInConf($tsparser->setup);
            
            // Copy the resulting setup back into conf
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($this->settings,$tsparser->setup);
        }
    }
	
	/**
	* cf https://wiki.typo3.org/Exception/CMS/1297759968 
	* corrige l'erreur: Exception while property mapping at property path "":It is not allowed to map property "badproperty". 
	* You need to use $propertyMappingConfiguration->allowProperties('badproperty') to enable mapping of this property.
	* 
	* @return void
	*/
	protected function allowProperties($property){
		$propertyMappingConfiguration = $this->arguments[$property]->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->setTypeConverterOption(
			'TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter', 
			\TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, 
			TRUE
		);
	}
	protected function initializeEditLienAction(){
		$this->allowProperties('lien');
	}
	/**
	* @return void
	*/
	protected function initializeCreateLienAction(){
		$this->allowProperties('lien');
	}
	/**
	* @return void
	*/
	protected function initializeCreateMandatAction(){
		$this->allowProperties('mandat');
	}
	/**
	* @return void
	*/
	protected function initializeEditMandatAction(){
		$this->allowProperties('mandat');
	}
	/**
	* @return void
	*/
	protected function initializeCreateDocumentsAction(){
		$this->allowProperties('documents');
	}
	/**
	* @return void
	*/
	protected function initializeEditDocumentsAction(){
		$this->allowProperties('documents');
	}
	/**
	* @return void
	*/
	protected function initializeEditAction(){
		$this->allowProperties('fiche');
	}
	/**
	* @return void
	*/
	protected function initializeCreateAction(){
		$this->allowProperties('fiche');
	}

	/**
	 * action manage 
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche
	 * @param \string $status
	 * @return void
	 */
	public function manageAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche = NULL, $status = '') {
		$currentRequest = $this->request->getArguments();
								
		// On donne une valeur à la variable $status selon le cas de figure:
		// updateDone si on vient de mettre à jour la fiche
		// no_user si il n'y a pas d'utilisateur fe connecté
		// Si aucun des deux cas précédents pas de status
		if(empty($this->currentUser)){
			$status = 'no_user';
		}
		
		// Si on a pas de status, on affiche le formulaire adéquat
		if($fiche === NULL){
			$fichesUser = $this->ficheRepository->getFichesUser($this->currentUser['uid']);
			$this->view->assign('fiches', $fichesUser);
		} else {
			$this->forward('edit',null,null,array('fiche'=>$fiche));
		}
		
		$this->view->assign('status',$status);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($fiche, $status, $this));
	}

	/**
	 * action edit -> Display edition form (only display)
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche
	 * @return void
	 */
	public function editAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche = NULL) {		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAssignObject', array($fiche, $this));
		
		// On transmet les settings au repository. Utilisé pour la génération des tableaux
        $this->ficheRepository->setSettings($this->settings, array('cObj' => $this->configurationManager->getContentObject()));	
		
		// Si aucune fiche n'est passé en paramètre (=création d'une nouvelle fiche), on fait l'instanciation à la main
		if($fiche === NULL){
			$newFiche = $this->objectManager->get('Emagineurs\EAnnuaires\Domain\Model\Fiche');
		}
		
		// On assigne la fiche au formulaires
		$this->view->assign('fiche', $fiche);
		
		// Gestion des tableaux pour les selects du TCA
		$this->handleArrayAssignitionForFeEdition();
				
		//Ajout du js pour les inlines
		$this->addJsEditFe();
		
		if(!empty($this->currentUser)){
			$this->view->assign('user',$this->currentUser);
		}
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($fiche, $this));
	}
	
	/**
	 * Ajout du javascript associé au formulaire d'edition frontend (but initial gestion des inlines)
	 */
	public function addJsEditFe(){
		$jsPath = !empty($this->settings['editFe']['jsPath']) 
				  ? $this->settings['editFe']['jsPath'] 
				  : \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('e_annuaires').'Resources/Public/Js/eannuairesFrontend.js';
		
		if(empty($GLOBALS['TSFE']->additionalFooterData[$this->extKey])){
			$GLOBALS['TSFE']->additionalFooterData[$this->extKey] = '
				<script type="text/javascript" src="'.$jsPath.'"></script>
			';
		}
	}

	/**
	 * Gestion de l'assignation des tableaux pour l'edition frontend
	 * Pas de return car tout ce qui importe dans cette fonction, c'est le $this->assign
	 */
	public function handleArrayAssignitionForFeEdition() {		
		// On utilise la même fonction pour générer les tableaux du formulaire d'edition frontend que pour générer ceux de la recherche
		// Par défaut, on retrouve une conf pour tous les select dans la conf static 
		// n.b : ne concerne que les champs étant des select de base, si un input est changé en select via surcharge du TCA, il faudrait faire toute la conf à la main
        $properties = $this->settings['editFe']['arrays'];
		
		// On récupère également la conf du filtre par type, si elle est défini on ne génère que les tableaux correspondant au type indiqué
		$currentType = $this->settings['editFe']['defaultValues']['typeelement'];
		
		// Si on a un type de défini dans le flexform, on ne génère que les tableaux correspondants aux select de ce type
		if($this->champBddRepository->selectConfType($currentType)->count() > 0){
			$champs = $this->champBddRepository->selectConfType($currentType);
		} else {
			// Sinon, on le fait pour tous les champs générés dans la conf
			
			// On récupère la liste des champs configuré 
			$allChamps = $this->champBddRepository->findAll();
			// n.b : solution concurrentes -> utilisé la fonction admin_get_fields qui récupère tous les champs de la table,
			// mais ne renvoie pas d'objet donc plus compliqué pour ensuite traiter tous les champs via une seule et même fonction
			// $champs = $GLOBALS['TYPO3_DB']->admin_get_fields('tx_eannuaires_domain_model_fiche');
			
			// On dédoublonne (pas de array_unique car pour un même champs on pourra avoir 2 objets différents
			// s'il sont utilisé dans 2 type d'annuaires différents).
			if(count($allChamps) > 0){
				$arrayVerif = array();
				$champs = array();
				foreach($allChamps as $currentChamp){
					if(!in_array($currentChamp->getTitle(),$arrayVerif)){
						$champs[] = $currentChamp;
						$arrayVerif[] = $currentChamp->getTitle();
					}
				}
			}
		}
		
		$this->handleArrayForDefinedType($champs,$properties);
	}

	/**
	 * Gestion des tableaux
	 */
	public function handleArrayForDefinedType($champs,$properties) {
		foreach($champs as $currentField){
			// On n'applique la config que pour les champs select du tca
			if($GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$currentField->getTitle()]['config']['type'] == 'select'){
				// On prepare la "variable" que l'on assigne, en accord avec ce qui a été défini dans les templates générés par le module 
				$currentArray = 'array'.ucFirst($currentField->getTitle());
				
				// On récupère la conf TS correspondantes 
				$currentProperty = $properties[$currentArray];
				
				// Si on trouve une conf, on lance la génération du tableau
				if(is_array($currentProperty)){
					$this->assignArrayFromTca($currentProperty,$currentArray,$currentField);
				}
			}
		}
	}

	/**
	 * On assigne à la vue les tableaux correspondant aux select que l'on remonte dans le formulaire d'edition frontend
	 */
	public function assignArrayFromTca($currentProperty,$currentArray,$currentField) {
		// Si le champ est identifé en TS comme basé sur du tsconfig
		if(array_key_exists('fromTsconfig',$currentProperty)){
			$arrayPath = explode('.',$currentProperty['fromTsconfig']);
			$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
			
			if(count($arrayPath) > 0){
				// On a le chemin dans la ts config, on la parcore donc etape par etape en gardant a chaque fois le resultat
				foreach($arrayPath as $step){
					$tsconfig = $tsconfig[$step.'.'];
				}
				
				$this->view->assign($currentArray, $tsconfig);
			}
		} else if(array_key_exists('manualList',$currentProperty)){
			// Si le champ est identifé en TS comme basé sur des items défini à la main dans le TCA
			$manuallySetItems = $GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$currentField->getTitle()]['config']['items'];
			$arrayToAssign = array();
			foreach($manuallySetItems as $item){
				$arrayToAssign[$item[1]] = $item[0];
			}
			
			$this->view->assign($currentArray, $arrayToAssign);
		} else {
			// Sinon, le tableau est généré à partir d'enregistrements de la bdd (même fonction que pour les tableaux de la recherche)
			$arrayToAssign = $this->utility->getArrayForSearchForm($currentProperty,$this->settings);
			$this->view->assign($currentArray, $arrayToAssign);
		}
	}

	/**
	 * action create -> create the new fiche then redirect to manageAction
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche
	 * @return void
	 */
	public function createAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche = null) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAdd', array($fiche, $this));
		
		$status = '';
		if($fiche instanceof \Emagineurs\EAnnuaires\Domain\Model\Fiche && $fiche !== null){
			// On set le feuser qui a créé la fiche
			$fiche = $this->setFeuserFiche($fiche);
		
			// On set les autres champs indispensables mais non editable par l'utilisateur (défini dans le flexform par défaut)
			$fiche = $this->setPropertiesFromSettingsDatas($fiche);
			
			// On set les inlines à partir des données en sessions (création d'enregistrements + renvoi de l'objet correspondant)
			$fiche = $this->setInlines($fiche);
			
			if($fiche->getUid()){				
				$this->ficheRepository->update($fiche);
				$status = 'update';
			} else {
				$this->ficheRepository->add($fiche);
				$status = 'add';
			}
			
			$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$persistenceManager->persistAll();
		}
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRedirect', array($fiche, $status, $this));
		
		$this->forward('manage',null,null,array('status' => $status));
	}
	
	public function setFeuserFiche($fiche){
		// On set à la fiche le feuser qui lui correspond
		$this->feuserRepo = $this->objectManager->get(\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository::class);
		$currentFeuserObj = $this->feuserRepo->findByUid($this->currentUser['uid']);

		$feuserStorage = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class);
		$feuserStorage->attach($currentFeuserObj);
	
		$fiche->setFeuser($feuserStorage);
		
		return $fiche;
	}
	
	public function setPropertiesFromSettingsDatas($fiche){		
		// On set les valeurs par défaut qui ont été défini dans le flexform (ne fonctionne pas pour les relations complexe entre table, mm par exemple)
		if(is_array($this->settings['editFe']['defaultValues']) && count($this->settings['editFe']['defaultValues']) > 0){
			foreach($this->settings['editFe']['defaultValues'] as $field => $defaultValue){
				$setter = 'set'.ucFirst($field);
				$fiche->$setter($defaultValue);
			}
		}
		
		return $fiche;
	}
	
	public function setInlines($fiche){
		// On parcoure le TCA pour trouver les champs de type inline
		foreach($GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'] as $annuaireField => $configField){
			if($configField['config']['type'] == 'inline'){
				$tableArray = explode('_',$configField['config']['foreign_table']);
				$objName = end($tableArray);
				
				$datasFromSessionForCurrentField = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-'.ucFirst($objName));
												
				// Si on a bien des données dans les sessions, on traite le champ
				if(!empty($datasFromSessionForCurrentField)){
					$objStorage = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class);
					$setterOj = 'set'.ucFirst($annuaireField);
										
					foreach($datasFromSessionForCurrentField as $currentInlineDatas){
						$objStorage = $this->setCurrentInline($objStorage, $objName, $currentInlineDatas, $fiche);
					}
					
					$fiche->$setterOj($objStorage);
					$this->cleanSessionsDatas('inlineForm-'.ucFirst($objName));
					$this->cleanSessionsDatas('inlineFormUpload');
				}
			}
		}
		
		return $fiche;
	}
	
	public function setCurrentInline($objStorage, $objName, $currentInlineDatas, $fiche){
		// Fonctionnement de base si tous les champs sont settables directement, 
		// on ajoutera par la suite les cas plus compliqué type FAL ou storage objects
		$modelName = 'Emagineurs\\EAnnuaires\\Domain\\Model\\'.ucFirst($objName);
		$repoName = 'Emagineurs\\EAnnuaires\\Domain\\Repository\\'.ucFirst($objName).'Repository';
		$obj = $this->objectManager->get($modelName);
		
		$schema = $objName.'ModelSchema'; 
		$modelConfig = $this->$schema->getProperties();
				
		// On set le pid
		$obj->setPid($fiche->getPid());
		
		foreach($currentInlineDatas as $field => $value){
			$setter = 'set'.ucFirst($field);
			
			if($modelConfig[$field]['type'] == 'TYPO3\CMS\Extbase\Persistence\ObjectStorage'){
				if(!empty($value)){
					$inlineObjStorage = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class);
					$model = $modelConfig[$field]['elementType'];
					if($model == 'Emagineurs\EAnnuaires\Domain\Model\Filereference'){
						$inlineObjStorage = $this->setCurrentInlineFile($obj, $value, $objName, $field, $inlineObjStorage);
					} else {					
						$repoArray = explode('\\',$model);
						$countModel = count($repoArray);
						$repoArray[$countModel-2] = 'Repository';
						$repoArray[$countModel-1] .= 'Repository';
						$inlineRepoName = implode('\\',$repoArray);
						$repo = $this->objectManager->get($inlineRepoName);
						$valueArray = explode(',',$value);
					
						foreach($valueArray as $currentValue){
							$inlineObj = $repo->findByUid($currentValue);
							$inlineObjStorage->attach($inlineObj);
						}
					}
					$obj->$setter($inlineObjStorage);
				}
			} else {
				$obj->$setter($value);
			}
		}
			
		// On crée en base de données l'enregistrement correspondant à l'objet de l'inline courant
		$repoObj = $this->objectManager->get($repoName);
		$repoObj->add($obj);
				
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
		
		// On l'ajoute ensuite à la fiche annuaire en cours de création
		$objStorage->attach($obj);
		
		return $objStorage;
	}
	
	public function setCurrentInlineFile($obj, $fileIdentifier, $objName, $field, $inlineObjStorage){
		$this->resourceFactory = $this->objectManager->get(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		
		foreach($fileIdentifier as $currentFileIdentifier){
			$fileRef = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Filereference::class);
			
			if(is_array($currentFileIdentifier)){
				$identifier = $currentFileIdentifier['identifier'];
				
				foreach($currentFileIdentifier as $fileProperty => $propertyValue){
					if($fileProperty != 'identifier'){
						$setter = 'set'.ucFirst($fileProperty);
						$fileRef->$setter($propertyValue);
					}
				}
			} else {
				$identifier = $currentFileIdentifier;
			}
			
			$file = $this->resourceFactory->getFileObjectByStorageAndIdentifier(1,$identifier);
			$originalResource = \Emagineurs\EAnnuaires\Utility\FileUtility::createFileReference($file);
						
			$fileRef->setOriginalResource($originalResource);
			$fileRef->setTableLocal('sys_file');
			$fileRef->setShowinpreview(TRUE);
			
			$inlineObjStorage->attach($fileRef);
		}
		
		return $inlineObjStorage;
	}
	
	public function cleanSessionsDatas($sessionToClean){
		$GLOBALS['TSFE']->fe_user->setKey('user', $sessionToClean, NULL);
	}
	
	/**
	 * action handleInlineGeneration -> Redirect to the right action according to the field param
	 *
	 * @param \string $table
	 * @param \string $item
	 * @return void
	 */
	public function removeInlineItemAction($table, $item){
		// On supprime dans sessions l'item inline que l'on ne souhaite plus
		$sessionKey = "inlineForm-".ucFirst($table);
		$datasFromSession = $GLOBALS['TSFE']->fe_user->getKey('user', $sessionKey);
		unset($datasFromSession[$item]);
		$datasFromSession = $GLOBALS['TSFE']->fe_user->setKey('user', $sessionKey, $datasFromSession);
		
		$this->forward('edit'.ucFirst($table),null,null,null);
	}
	
	/**
	 * action remove the clicked image
	 *
	 * @param \string $imageToRemove
	 * @param \string $uidFiche
	 * @return void
	 */
	public function removeFileAction($imageToRemove, $uidFiche){
		// On récupère la fiche courante afin de supprimer l'image qui lui est attaché.
		$fiche = $this->ficheRepository->findByUid($uidFiche);
		
		// On récupère la la filereference de l'image choisie
		$fileRefRepo = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Repository\FilereferenceRepository::class);
		$fileRefToDelete = $fileRefRepo->findByUid($imageToRemove);
		
		// On detach l'image de l'object storage puis on update
		$getter = 'get'.ucFirst($fileRefToDelete->getFieldname());
		$fiche->$getter()->detach($fileRefToDelete);
		$this->ficheRepository->update($fiche);
			
		// On persist pour rendre les modifications sur l'objet effectives en base de donnée
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
	}
	
	/**
	 * action remove the clicked image of an inline
	 *
	 * @param \string $imageToRemove
	 * @param \string $uidInline
	 * @param \string $typeInline
	 * @return void
	 */
	public function removeInlineImageAction($imageToRemove,$uidInline,$typeInline){
		// On instancie le repo de l'inline auquel on souhaite enlever une image
		$repoName = 'Emagineurs\\EAnnuaires\\Domain\\Repository\\'.ucFirst($typeInline).'Repository';
		$repository = $this->objectManager->get($repoName);
		
		// On récupère l'inline courant afin de supprimer l'image qui lui est attaché.
		$inline = $repository->findByUid($uidInline);
		
		// On récupère la la filereference de l'image choisie
		$fileRefRepo = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Repository\FilereferenceRepository::class);
		$fileRefToDelete = $fileRefRepo->findByUid($imageToRemove);
		
		// On detach l'image de l'object storage puis on update
		if($typeInline == 'documents'){
			$getter = 'getFichier';
		} elseif($typeInline == 'uploadimage'){
			$getter = 'getMedia';
		}
		if(!empty($getter)){
			$inline->$getter()->detach($fileRefToDelete);
			$repository->update($inline);
				
			// On persist pour rendre les modifications sur l'objet effectives en base de donnée
			$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$persistenceManager->persistAll();
		}
	}
	
	/**
	 * action handleInlineGeneration -> Redirect to the right action according to the field param
	 *
	 * @param \string $field
	 * @return void
	 */
	public function handleInlineGenerationAction($field){
		// Appelé via de l'ajax, redirige vers l'action correspondant au formulaire (pour un champ inline) que l'on souhaite affiché
		$action = 'edit'.ucFirst($field);
		$this->forward($action);
	}
	
	/**
	 * action handleInlineSubmit -> Redirect to the right action according to the field param
	 *
	 * @param \array $dataForm
	 * @return void
	 */
	public function handleInlineSubmitAction($dataForm = array()){		
		// on modifie le contenu du champ name pour généré un tableau qui permettra d'avoir un parametre pour l'action vers laquelle on va être redirigé
		$arrayFromDatas = explode('[',$dataForm[0]['name']);
		$table = substr($arrayFromDatas[1],0,-1);
		
		$formDatas = array();
		
		foreach($dataForm as $datas){
			$arrayFromCurrentDatas = explode('[',$datas['name']);
			
			if(!empty(substr(end($arrayFromCurrentDatas),0,-1))){
				$field = substr(end($arrayFromCurrentDatas),0,-1);
			} else {
				$count = count($arrayFromCurrentDatas);
				$field = substr($arrayFromCurrentDatas[$count-2],0,-1);
			}
			
			if(empty($formDatas[$field])){
				$formDatas[$field] = $datas['value'];
			} else {
				$formDatas[$field] .= ','.$datas['value'];
			}
		}
		
		$action = 'create'.ucFirst($table);
		$this->forward($action,null,null,array($table => $formDatas));
	}
	
	/**
	 * action uploadInlineImage -> create the fal entry for the inline record that is being created
	 *
	 * @return void
	 */
	public function uploadInlineImageAction(){
		if(!empty($_FILES)){
			foreach($_FILES as $key => $fileInfo){
				if(!empty($fileInfo['name'])){					
					$file = \Emagineurs\EAnnuaires\Utility\FileUtility::generateFile($fileInfo);		
					
					$fileSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineFormUpload');
					
					if($fileSession === null){
						$fileSession = array();
					}
					$keyArray = explode('-',$key);					
					
					$fileSession[$keyArray[0]][$keyArray[1]][] = $file->getIdentifier();
					
					$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineFormUpload',$fileSession);
					
					$this->view->assign('image',$file);
				}
			}
		}
	}
	
	/**
	 * add fields that cannot be sent during the upload of the file
	 *
	 * @param array $fileInfo
	 * @return void
	 */
	public function addFileInfoAction($fileInfo = array()){
		$fileSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineFormUpload');
		
		if(count($fileInfo) > 0){
			$newFileArray = array();
			
			// On va se basé sur le nom du champ pour retrouver l'entrée dans les sessions qui nous interesse
			$firstFileInfo = reset($fileInfo);
			$firstFileInfoName = $firstFileInfo['name'];
			$nameArray = explode('-',$firstFileInfoName);
			
			if(!empty($fileSession[$nameArray[0]][$nameArray[1]])){
				$currentFile = array_pop($fileSession[$nameArray[0]][$nameArray[1]]);
				$newFileArray['identifier'] = $currentFile;
				
				foreach($fileInfo as $currentFileInfo){
					$currentNameArray = explode('-',$currentFileInfo['name']);
					$newFileArray[$currentNameArray[2]] = $currentFileInfo['value'];
				}
				
				$fileSession[$nameArray[0]][$nameArray[1]][] = $newFileArray;
				$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineFormUpload', $fileSession);
			}
		}
		
		return TRUE;
	}
	
	/**
	 * action edition frontend pour la table tx_eannuaires_domain_model_lien
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Lien $lien
	 * @return void
	 */
	public function editLienAction(\Emagineurs\EAnnuaires\Domain\Model\Lien $lien = null){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeginningAction', array($lien, $this));
		
		if($lien === null){
			$lien = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Lien::class);
		}
		
		$datasFromSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Lien');
		$this->view->assign('submittedLien', $datasFromSession);
		
		$this->view->assign('lien', $lien);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'EndAction', array($lien, $this));
	}
	
	/**
	 * action edition frontend pour la table tx_eannuaires_domain_model_lien
	 *
	 * @param \array $lien
	 * @return void
	 */
	public function createLienAction($lien){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAdd', array($lien, $this));
				
		$existingLien = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Lien');
		
		if(empty($existingLien)){
			$existingLien = array();
		}
		
		$existingLien[] = $lien;
			
		/*** PAS DE CREATION D'ENREGISTREMENT ICI, ON ATTEND LA SOUMISSION DU FORMULAIRE POUR EVITER DE CREER DES ENREGISTREMENTS INUTILE SI LA PERSONNE NE VALIDE PAS ***/
		/*** A LA PLACE ON STOCKE EN SESSION, ON GENERERA LES OBJET PLUS TARD ***/
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineForm-Lien', $existingLien);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRedirect', array($lien, $this));
		
		$this->forward('editLien',null,null,array());
	}
	
	/**
	 * action edition frontend pour la table tx_eannuaires_domain_model_mandat
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Mandat $mandat
	 * @param \integer $typeMandat
	 * @return void
	 */
	public function editMandatAction(\Emagineurs\EAnnuaires\Domain\Model\Mandat $mandat = null, $typeMandat = 1){		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeginningAction', array($mandat, $this));
		
		if($mandat === null){
			$mandat = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Mandat::class);
			$mandat->setTypemandat($typeMandat);
		}
		
		$datasFromSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Mandat');
		$this->view->assign('submittedMandat', $datasFromSession);
		
		// On génère les tableaux pour les select du template
		$this->generateMandatArrays($typeMandat);
		
		$this->view->assign('typeMandat', $typeMandat);
		$this->view->assign('mandat', $mandat);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'EndAction', array($mandat, $this));
	}
	
	public function generateMandatArrays($typeMandat){
		// liste des arrays :
		$mandatArrays = array('typemandat', 'communemandat', 'groupepolitique', 'circonscription', 'nomepci', 'canton', 'fonctions');
		
		foreach($mandatArrays as $arrayToGenerate){
			$method = 'get'.ucFirst($arrayToGenerate).'Array';
			$this->view->assign($arrayToGenerate,$this->$method($typeMandat));
		}
	}
	
	public function getTypemandatArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$currentArray = (!empty($tsconfig['tx_eannuaires.']['itemTypeMandat.'])) ? $tsconfig['tx_eannuaires.']['itemTypeMandat.'] : NULL ;
		return $currentArray;
	}
	
	public function getCommunemandatArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$typeElement = $tsconfig['TCEFORM.']['tx_eannuaires_domain_model_fiche.']['typeelement.']['PAGE_TSCONFIG_ID'];

		if(intval($typeElement) > 0){
			$listeCommune = $this->ficheRepository->findByTypeelement($typeElement)->toArray();
			$communeArray = array();
			
			if(is_array($listeCommune) && count($listeCommune) > 0){
				foreach($listeCommune as $commune){
					$communeArray[$commune->getUid()] = $commune->getTitle();
				}
			}
			
			return $communeArray;
		} else {
			return NULL;
		}
	}
	
	public function getGroupepolitiqueArray($typeMandat){
		$groupepolitiqueRepo = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Repository\GroupepolitiqueRepository::class);
				
		if(intval($typeMandat) > 0){
			$listeGroupe = $groupepolitiqueRepo->findByTypemandat($typeMandat)->toArray();		
			$groupeArray = array();
			
			if(is_array($listeGroupe) && count($listeGroupe) > 0){
				foreach($listeGroupe as $groupe){
					$groupeArray[$groupe->getUid()] = $groupe->getIntitule();
				}
			}
			
			return $groupeArray;
		} else {
			return NULL;
		}       
	}
	
	public function getCirconscriptionArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$currentArray = (!empty($tsconfig['tx_eannuaires.']['itemCircLegislative.'])) ? $tsconfig['tx_eannuaires.']['itemCircLegislative.'] : NULL ;
		return $currentArray;
	}
	
	public function getNomepciArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$pid = $tsconfig['TCEFORM.']['tx_eannuaires_domain_model_fiche.']['pid.']['PAGE_TSCONFIG_ID'];

		if(intval($pid) > 0){
			$listeEpci = $this->ficheRepository->findByPid($pid)->toArray();
			$epciArray = array();
			
			if(is_array($listeEpci) && count($listeEpci) > 0){
				foreach($listeEpci as $epci){
					$epciArray[$epci->getUid()] = $epci->getTitle();
				}
			}
			
			return $epciArray;
		} else {
			return NULL;
		}
	}
	
	public function getCantonArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$currentArray = (!empty($tsconfig['tx_eannuaires.']['itemCanton.'])) ? $tsconfig['tx_eannuaires.']['itemCanton.'] : NULL ;
		return $currentArray;
	}
	
	public function getFonctionsArray($typeMandat){
		$tsconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($GLOBALS['TSFE']->id);
		$currentArray = (!empty($tsconfig['tx_eannuaires.']['itemFonctions.'])) ? $tsconfig['tx_eannuaires.']['itemFonctions.'] : NULL ;
		return $currentArray;
		
	}
	
	/**
	 * action edition frontend pour la table tx_eannuaires_domain_model_mandat
	 *
	 * @param \array $mandat
	 * @return void
	 */
	public function createMandatAction($mandat){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAdd', array($mandat, $this));
				
		$existingMandat = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Mandat');
		
		if(empty($existingMandat)){
			$existingMandat = array();
		}
		
		$existingMandat[] = $mandat;
				
		/*** PAS DE CREATION D'ENREGISTREMENT ICI, ON ATTEND LA SOUMISSION DU FORMULAIRE POUR EVITER DE CREER DES ENREGISTREMENTS INUTILE SI LA PERSONNE NE VALIDE PAS ***/
		/*** A LA PLACE ON STOCKE EN SESSION, ON GENERERA LES OBJET PLUS TARD ***/
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineForm-Mandat', $existingMandat);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRedirect', array($mandat, $this));
		
		$this->forward('editMandat',null,null,array());
	}
	
	/**
	 * action edition frontend pour le champ documents > champ du FAL
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Documents $documents
	 * @return void
	 */
	public function editDocumentsAction(\Emagineurs\EAnnuaires\Domain\Model\Documents $documents = null){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeginningAction', array($documents, $this));
		
		if($documents === null){
			$documents = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Documents::class);
		}
		
		$datasFromSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Documents');
		$this->view->assign('submittedDocuments', $datasFromSession);
		
		$this->view->assign('documents', $documents);
				
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
		$typedisplayArray = explode(',',$conf['typedisplay']);
		$typedisplay = array();
		
		foreach($typedisplayArray as $type){
			$typedisplay[$type] = $type;
		}
		
		$this->view->assign('typedisplay', $typedisplay);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'EndAction', array($documents, $this));
	}
	
	/**
	 * action edition frontend pour le champ documents > champ du FAL
	 *
	 * @param \array $documents
	 * @return void
	 */
	public function createDocumentsAction($documents){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAdd', array($documents, $this));
				
		$existingDocuments = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Documents');
		
		$existingFiles = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineFormUpload');
		$existingFilesSave = $existingFiles;
						
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineFormUpload', null);
		
		if(is_array($existingFilesSave) && count($existingFilesSave) > 0){
			foreach($existingFilesSave['documents'] as $field => $currentFileIdentifier){
				$documents[$field] = $currentFileIdentifier;
			}
		}
		
		if(empty($existingDocuments)){
			$existingDocuments = array();
		}
		
		$existingDocuments[] = $documents;
							
		/*** PAS DE CREATION D'ENREGISTREMENT ICI, ON ATTEND LA SOUMISSION DU FORMULAIRE POUR EVITER DE CREER DES ENREGISTREMENTS INUTILE SI LA PERSONNE NE VALIDE PAS ***/
		/*** A LA PLACE ON STOCKE EN SESSION, ON GENERERA LES OBJET PLUS TARD ***/
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineForm-Documents', $existingDocuments);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRedirect', array($documents, $this));
		
		$this->forward('editDocuments',null,null,array());
	}
	
	/**
	 * action edition frontend pour le champ Uploadimage > champ du FAL
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Image $uploadimage
	 * @return void
	 */
	public function editUploadimageAction(\Emagineurs\EAnnuaires\Domain\Model\Image $uploadimage = null){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeginningAction', array($uploadimage, $this));
		
		if($uploadimage === null){
			$uploadimage = $this->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Image::class);
		}
		
		$datasFromSession = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Image');
		$this->view->assign('submittedUploadimage', $datasFromSession);
		
		$this->view->assign('uploadimage', $uploadimage);
				
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'EndAction', array($uploadimage, $this));
	}
	
	/**
	 * action edition frontend pour le champ uploadimage > champ du FAL
	 *
	 * @param \array $uploadimage
	 * @return void
	 */
	public function createUploadimageAction($uploadimage){
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeAdd', array($uploadimage, $this));
				
		$existingUploadimage = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineForm-Image');
		
		$existingFiles = $GLOBALS['TSFE']->fe_user->getKey('user', 'inlineFormUpload');
		$existingFilesSave = $existingFiles;
						
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineFormUpload', null);
		
		if(is_array($existingFilesSave) && count($existingFilesSave) > 0){
			foreach($existingFilesSave['uploadimage'] as $field => $currentFileIdentifier){
				$uploadimage[$field] = $currentFileIdentifier;
			}
		}
		
		if(empty($existingUploadimage)){
			$existingUploadimage = array();
		}
		
		$existingUploadimage[] = $uploadimage;
							
		/*** PAS DE CREATION D'ENREGISTREMENT ICI, ON ATTEND LA SOUMISSION DU FORMULAIRE POUR EVITER DE CREER DES ENREGISTREMENTS INUTILE SI LA PERSONNE NE VALIDE PAS ***/
		/*** A LA PLACE ON STOCKE EN SESSION, ON GENERERA LES OBJET PLUS TARD ***/
		$GLOBALS['TSFE']->fe_user->setKey('user', 'inlineForm-Image', $existingUploadimage);
		
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRedirect', array($uploadimage, $this));
		
		$this->forward('editUploadimage',null,null,array());
	}

	/**
	 * Set fields edited through a RTE
	 */
	public function setRteValues($rte,$fiche){
		if(is_array($rte) && !empty($rte)){
			// Pour chaque champ identifé comme un rte dans le template fluid,
			foreach($rte as $key => $value){
				// On récupère la cellule du tableau des RTE qui contient la valeur à mettre à jour
				if(strpos($key,'_TRANSFORM_') === false){
					// On génère le nom du setter correspondant
					$setter = 'set'.ucFirst($key);
					// On l'appelle pour modifier la valeur de l'objet fiche
					$fiche->$setter($value);
				}
			}
			return $fiche;
		}
	}

	/**
	 * action delete
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche
	 * @return void
	 */
	public function deleteAction(\Emagineurs\EAnnuaires\Domain\Model\Fiche $fiche) {
		$this->ficheRepository->remove($fiche);
		
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
			
		$this->forward('manage',null,null,array('status' => 'deleted'));
	}
	
}
?>