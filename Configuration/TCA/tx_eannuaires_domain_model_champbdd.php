<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tx_eannuaires_domain_model_champbdd = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_pack',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => '',
		
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
        'type' => 'typemandat',	
		'hideTable' => TRUE,
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => '',
		'iconfile' => 'EXT:e_annuaires/Resources/Public/Icons/tx_eannuaires_domain_model_actions.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_eannuaires_domain_model_champbdd',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_champbdd.pid=###CURRENT_PID### AND tx_eannuaires_domain_model_champbdd.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_champbdd.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),		
		'label' => array(
			'exclude' => 0,
			'label' => 'nouveau label du champ',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),		
		'tablabel' => array(
			'exclude' => 0,
			'label' => 'label du nouvel onglet',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),	
		'typeannuaire' => array(
			'exclude' => 0,
			'label' => 'type auquel appartient le champ',
			'config' => array(
				'type' => 'select',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_eannuaires_domain_model_typeannuaire',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_typeannuaire.hidden=0 AND tx_eannuaires_domain_model_typeannuaire.deleted=0',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),	
		'sorting' => array(
			'exclude' => 0,
			'label' => 'sorting',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),	
	),
);

return $tx_eannuaires_domain_model_champbdd;
?>