<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Emagineurs.' . $_EXTKEY,
	'Pi1',
	array(
		'Fiche' => 'list, search, resultat, show, catmenu, sendMail',
		'Editfe' => 'manage, edit, create, handleInlineGeneration, editLien, createLien, delete, handleInlineSubmit, removeInlineItem'
	),
	// non-cacheable actions
	array(	
		'Fiche' => 'search, resultat, sendMail',
		'Editfe' => 'manage, edit, create, handleInlineGeneration, editLien, createLien, delete, handleInlineSubmit, removeInlineItem'
	)
);

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['txeannuaires_search_cache'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['txeannuaires_search_cache'] = array();
}

// Hook pour inclure la conf dans $GLOBALS avant d'arriver dans le TCA
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass']['e_annuaires'] = 'Emagineurs\EAnnuaires\Hooks\IncludeConf';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['e_annuaires'] = 'Emagineurs\EAnnuaires\Hooks\IncludeConf';

// eid pour l'AJAX du module
// $GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['EAnnuaires'] = 'Emagineurs\EAnnuaires\Ajax\Conf->main';
$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['EAnnuaires'] = [
	'callbackMethod' => 'Emagineurs\EAnnuaires\Ajax\Conf->main',
	'csrfTokenCheck' => false
];

// COnfiguration des templates proposés par défaut
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['liste'] = array(
	30 => 'Personnes',
	40 => 'Galerie vidéo'
);
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['detail'] = array(
	30 => 'Personnes',
	40 => 'Galerie vidéo'
);
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['catmenu'] = array();
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['search'] = array(
	30 => 'Personnes',
	40 => 'Galerie vidéo'
);
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['result'] = array(
	30 => 'Personnes',
	40 => 'Galerie vidéo'
);
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['manage'] = array();
$GLOBALS['TYPO3_CONF_VARS']['EXT']['e_annuaires']['defaultLayout']['edit'] = array();

?>