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

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\InlineStackProcessor;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\FrontendEditing\FrontendEditingController;
use TYPO3\CMS\Core\Html\RteHtmlParser;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ClientUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Lang\LanguageService;
use TYPO3\CMS\Rtehtmlarea\RteHtmlAreaApi;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class FrontendRte extends \TYPO3\CMS\Rtehtmlarea\Form\Element\RichTextElement {


    /**
     * This will render a <textarea> OR RTE area form field,
     * possibly with various control/validation features
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render() {
        $table = $this->data['tableName'];
        $fieldName = $this->data['fieldName'];
        $row = $this->data['databaseRow'];
        $parameterArray = $this->data['parameterArray'];

		// On supprime ce qui dépend du backend pour essayer d'avoir un rendu en frontend
        // $backendUser = $this->getBackendUserAuthentication();
		
		DebuggerUtility::var_dump($this->data,'TEST $this->data');

        $this->resultArray = $this->initializeResultArray();
        $this->defaultExtras = BackendUtility::getSpecConfParts($parameterArray['fieldConf']['defaultExtras']);
        $this->pidOfPageRecord = $this->data['effectivePid'];
        BackendUtility::fixVersioningPid($table, $row);
        $this->pidOfVersionedMotherRecord = (int)$row['pid'];
		
		// On va changer la méthode de récuperation de la TSconfig
        // $this->vanillaRteTsConfig = $backendUser->getTSConfig('RTE', BackendUtility::getPagesTSconfig($this->pidOfPageRecord));
		$tsconfigPath = $this->data['tsConfigPath'];
		$tsconfigAbsPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($tsconfigPath);
		$tsConfText = file_get_contents($tsconfigAbsPath);
		$tsConfigParser = \TYPO3\CMS\Core\Utility\generalUtility::makeInstance('TYPO3\CMS\Backend\Configuration\TsConfigParser');
		$parsedConf = $tsConfigParser->parseTSconfig($tsConfText,'PAGES');
		$this->vanillaRteTsConfig = $parsedConf['TSconfig']['RTE.'];
		
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->vanillaRteTsConfig,'TEST $this->vanillaRteTsConfig');
		
        $this->processedRteConfiguration = BackendUtility::RTEsetup(
            $this->vanillaRteTsConfig['properties'],
            $table,
            $fieldName,
            $this->data['recordTypeValue']
        );
        $this->client = $this->clientInfo();
        $this->domIdentifier = preg_replace('/[^a-zA-Z0-9_:.-]/', '_', $parameterArray['itemFormElName']);
        $this->domIdentifier = htmlspecialchars(preg_replace('/^[^a-zA-Z]/', 'x', $this->domIdentifier));

        $this->initializeLanguageRelatedProperties();

        // Get skin file name from Page TSConfig if any
        $skinFilename = trim($this->processedRteConfiguration['skin']) ?: 'EXT:rtehtmlarea/Resources/Public/Css/Skin/htmlarea.css';
        $skinFilename = $this->getFullFileName($skinFilename);
        $skinDirectory = dirname($skinFilename);

        // jQuery UI Resizable style sheet and main skin stylesheet
        $this->resultArray['stylesheetFiles'][] = $skinDirectory . '/jquery-ui-resizable.css';
        $this->resultArray['stylesheetFiles'][] = $skinFilename;

        $this->enableRegisteredPlugins();

        // Configure toolbar
        $this->setToolbar();

        // Check if some plugins need to be disabled
        $this->setPlugins();

        // Merge the list of enabled plugins with the lists from the previous RTE editing areas on the same form
        $this->pluginEnabledCumulativeArray = $this->pluginEnabledArray;

        $this->addInstanceJavaScriptRegistration();

        $this->addOnSubmitJavaScriptCode();

        // Add RTE JavaScript
        $this->loadRequireModulesForRTE();

        // Create language labels
        $this->createJavaScriptLanguageLabelsFromFiles();

        // Get RTE init JS code
        $this->resultArray['additionalJavaScriptPost'][] = $this->getRteInitJsCode();

        $html = $this->getMainHtml();

        $this->resultArray['html'] = $this->renderWizards(
            array($html),
            $parameterArray['fieldConf']['config']['wizards'],
            $table,
            $row,
            $fieldName,
            $parameterArray,
            $parameterArray['itemFormElName'],
            $this->defaultExtras,
            true
        );

        return $this->resultArray;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService() {
		$lang = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Lang\LanguageService');
		return $lang;
        // return $GLOBALS['LANG'];
    }
	
}
?>