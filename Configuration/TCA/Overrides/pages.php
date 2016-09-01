<?php
$locallang_path = 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:';

$fieldConfAnnuaire = array(
	'typeannuaire' => array(
		'exclude' => 0,
		'label' => $locallang_path.'pages.typeannuaire',
		'config' => array(
			'items' => array(
				array('', 0)
			),
			'foreign_table' => 'tx_eannuaires_domain_model_typeannuaire', 
			'foreign_table_where' => 'AND tx_eannuaires_domain_model_typeannuaire.hidden=0 AND tx_eannuaires_domain_model_typeannuaire.deleted=0 ', 
			'maxitems' => '1',
			'minitems' => '0',
			'size' => '1',
			'type' => 'select',
			'renderType' => 'selectSingle',
		)
	)
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $fieldConfAnnuaire);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'typeannuaire', '', 'before:TSconfig');


?>