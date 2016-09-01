<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Annuaires'
);

$extensionName = TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_fiche.xml');

$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] = TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Resources/Private/Php/class.' . $pluginSignature . '_wizicon.php';

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Annuaires');

if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Emagineurs.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'annuaires',	// Submodule key
		'',						// Position
		array(
			'Conf\Conf'=>'index,export,import,convert,createType',
			'Conf\Form'=>'manageForm',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_annuaires.xlf',
		)
	);
}

foreach (
	['fiche', 'city', 'district', 'documents', 'lien', 'image', 'commission', 'actions', 'objet', 'mandat', 'groupepolitique', 'pack', 'typeannuaire', 'champbdd'] as $table
) {
	TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_eannuaires_domain_model_' . $table);
	if($table != 'typeannuaire' && $table!= 'champbdd'){
		TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_eannuaires_domain_model_' . $table, 
			'EXT:e_annuaires/Resources/Private/Language/locallang_csh_tx_eannuaires_domain_model_' . $table . '.xlf'
		);
	}
}


?>