<?php
namespace Emagineurs\EAnnuaires\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Ian Sebbagh <isebbagh@e-magineurs.com>
 *  (c) 2014 Cyrille Costa <ccosta@e-magineurs.com>
 *  (c) 2014 Vincent Scharpen <xley@e-magineurs.com>
 *  (c) 2014 Xavier Ley <xley@e-magineurs.com>
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
class AbstractLib {

	public function getRepository($repoName) {		
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repo = $objectManager->get($repoName);
		return $repo;
	}
	
	public function getCObj(){		
		$contentObject = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
		$contentObject->start($contentObject->data);
		return $contentObject;
	}
    
    function initAjax(){		
		// #INIT TSFE
		if(!$id){
			$id = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
		}
		$id = intval($id);
		
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $id, 0);
		
		\TYPO3\CMS\Frontend\Utility\EidUtility::initLanguage();
		\TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
		
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->set_no_cache();
		$GLOBALS['TSFE']->checkAlternativeIdMethods();
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		$GLOBALS['TSFE']->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$GLOBALS['TSFE']->settingLanguage();
		$GLOBALS['TSFE']->settingLocale();
		
		\TYPO3\CMS\Frontend\Page\PageGenerator::pagegenInit();
		
		return $GLOBALS['TSFE'];
    }

    function callExtbaseAction($conf){  
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        
		$request = $objectManager->get('TYPO3\CMS\Extbase\Mvc\Web\Request');
		$request->setcontrollerExtensionName($conf['extensionName']);
		if(!empty($conf['pluginName'])){
			$request->setPluginName($conf['pluginName']);
		}
		$request->setControllerVendorName($conf['vendor']);
		$request->setControllerName($conf['controller']);
		$request->setControllerActionName($conf['action']);
		$request->setArguments($conf['arguments']);

		$response = $objectManager->get('TYPO3\CMS\Extbase\Mvc\ResponseInterface');
		$dispatcher = $objectManager->get('TYPO3\CMS\Extbase\Mvc\Dispatcher');
		$dispatcher->dispatch($request, $response);
		$content =  $response->getContent();

        return $content;
    }

}
?>