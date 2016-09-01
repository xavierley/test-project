<?php
namespace Emagineurs\EAnnuaires\Ajax;

Class Conf{
		
	public function __construct(){		
		// On instancie un sys_registry
		$this->registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');		
	}
		
	public function main(){
		$typeReq = $_POST['type'];
		
		if(is_array($_POST['request']) && !empty($_POST['request'])){
			foreach($_POST['request'] as $param => $value){
				$conf['arguments'][$param] = $value;
			}
		}			
				
		$method = 'handle'.ucFirst($typeReq);
		
		if(in_array($method,get_class_methods($this))){
			$result = $this->$method($conf);
		}
		
		echo json_encode($result);
		die;
	}
        
	public function handleSaveLocallang($conf){
		$message = ($this->saveLocallang($conf['arguments']['locallangPath'])) ? 'Le chemin du locallang a bien été enregistré' : 'L\'enregistrement du chemin a echoué';
		$result = array(
			'message' => $message
		);
		
		return $result;
	}	
	
	public function handleSaveNewType($conf){		
		$this->saveNewType($conf['arguments']['newTypeName']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');
		
		$message = ($sessionData['contentConf'] && $sessionData['contentType']) ? 0 : 'L\'enregistrement du type a echoué';
				
		$result = array(
			'message' => $message,
			'contentType' => $sessionData['contentType'],
			'contentConf' => $sessionData['contentConf'],
			'idType' => $sessionData['idType']
		);
								
		return $result;
	}	
	
	public function handleDeleteType($conf){	
		$this->deleteType($conf['arguments']['typeToDelete']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$message = ($sessionData['deleteSuccess']) ? 0 : 'La suppression du type a echoué';
		
		$result = array(
			'message' => $message,
			'typeToDelete' => $conf['arguments']['typeToDelete']
		);
					
		return $result;
	}	
	
	public function handleGenerateDetailConf($conf){	
		$this->generateDetailConf($conf['arguments']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = (trim($sessionData['generateDetailConf']) != '') ? $sessionData['generateDetailConf'] : 'La génération de la conf a echoué';
		
		$result = array(
			'content' => $content,
			'idType' => $conf['arguments']['idType']
		);
				
		return $result;
	}	
	
	public function handleAddNewField($conf){
		$this->addNewField($conf['arguments']['newFieldName'],$conf['arguments']['newFieldType'],$conf['arguments']['sorting']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = (trim($sessionData['addNewField']) != '') ? $sessionData['addNewField'] : 'L\'ajout du champ a echoué';
		
		$result = array(
			'content' => $content,
			'idType' => $conf['arguments']['newFieldType']
		);
						
		return $result;
	}	
	
	public function handleChangeSorting($conf){
		$args = array();
		$args['lowSorting'] = $conf['arguments']['lowSorting'];
		$args['highSorting'] = $conf['arguments']['highSorting'];
				
		$increaseSorting = $this->execControllerAction('changeSorting', $args);
	}	
	
	public function handleDeleteField($conf){
		$this->deleteField($conf['arguments']['fieldToDelete']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = ($sessionData['fieldToDelete'] === true) ? 1 : 'La suppresssion du champ a echoué';
		
		$result = array(
			'success' => $content,
			'idField' => $conf['arguments']['fieldToDelete']
		);
						
		return $result;
	}	
	
	public function handleUpdateLabelTab($conf){
		$this->updateLabelTab($conf['arguments']['updateArray'],$conf['arguments']['idType']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = (trim($sessionData['updateLabelTab']) != '') ? $sessionData['updateLabelTab'] : 'La sauvegarde des labels a echoué';
		
		$result = array(
			'content' => $content,
			'idType' => $conf['arguments']['idType']
		);
						
		return $result;
	}	
	
	public function handleGetTcaConf($conf){
		$this->getTcaConf($conf['arguments']['fieldName']);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = (trim($sessionData['getTcaConf']) != '') ? $sessionData['getTcaConf'] : 'L\'affichage de la conf a echoué';
		
		$result = array(
			'content' => $content,
			'idSelect' => $conf['arguments']['idSelect']
		);
						
		return $result;
	}	
	
	public function handleGenerateConf($conf){
		$this->generateConf();
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$content = ($sessionData['generateConf'] === true) ? 'La conf a bien été générée' : 'La génération de la conf a echoué';
		
		$result = array(
			'content' => $content
		);
						
		return $result;
	}
	
	public function handleGenerateFormTemplate($conf){
		$this->generateFormTemplate($conf);
		$sessionData = $GLOBALS['BE_USER']->getSessionData('tx_eannuaires');

		$message = ($sessionData['generateFormTemplate'] === true) ? 'Le formulaire a bien été générée' : 'La génération du formulaire a echoué';
		$content = $sessionData['generateFormTemplate'];
		
		$result = array(
			'message' => $message,
			'content' => $content
		);
						
		return $result;
	}	
	
	public function saveLocallang($locallangPath){				
		// On récupère la conf depuis la table des registres
		$conf = $this->registry->set('tx_eannuaires','locallangPath',$locallangPath);
		
		return true;
	}	
        
	public function saveNewType($newTypeName){
		$args = array();
		$args['newType'] = $newTypeName;
				
		$this->execControllerAction('createType', $args);
	}	
        
	public function deleteType($typeToDelete){
		$args = array();
		$args['typeToDelete'] = $typeToDelete;
				
		$type = $this->execControllerAction('deleteType', $args);
	}	
        
	public function generateDetailConf($conf){
		$args = array();
		$args = $conf;
				
		$this->execControllerAction('generateDetailConf', $args);
	}	
	
	public function addNewField($newFieldName,$newFieldType,$sorting){
		$args = array(
			'newFieldName' => $newFieldName,
			'newFieldType' => $newFieldType,
			'sorting' => $sorting
		);
		
		$this->execControllerAction('addNewField', $args);
	}
	
	public function deleteField($fieldToDelete){
		$args = array(
			'fieldToDelete' => $fieldToDelete
		);
		
		$this->execControllerAction('deleteField', $args);
	}
	
	public function updateLabelTab($updateArray,$idType){
		$args = array(
			'updateArray' => $updateArray,
			'idType' => $idType
		);
		
		$this->execControllerAction('updateLabelTab', $args);
	}
	
	public function getTcaConf($fieldName){
		$args = array(
			'fieldName' => $fieldName
		);
		
		$this->execControllerAction('getTcaConf', $args);
	}
	
	public function generateConf(){
		$this->execControllerAction('generateConf',array());
	}
	
	public function generateFormTemplate($conf){
		$this->execControllerAction('generateFormTemplate',$conf,'Conf\Form');
	}
	
	public function execControllerAction($action, $args, $controller = 'Conf\Conf'){
		$conf['vendor'] = 'Emagineurs';
		$conf['extensionName'] = 'EAnnuaires';
		$conf['pluginName'] = 'annuaires';
		$conf['controller'] = $controller;
		
		$conf['action'] = $action;
		$conf['arguments'] = $args;
		
		$contentObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Emagineurs\EAnnuaires\Utility\AbstractLib');
		$content = $contentObj->callExtbaseAction($conf);
		// return $content;
	}
	
}   

$conf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Emagineurs\EAnnuaires\Ajax\Conf');
$conf->main();

?>
