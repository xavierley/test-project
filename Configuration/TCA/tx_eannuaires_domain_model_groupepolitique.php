<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$locallang_path = 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:';

$tx_eannuaires_domain_model_groupepolitique = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_groupepolitique',
		'label' => 'intitule',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		// 'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY intitule',
		
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
        'type' => 'typemandat',	
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'intitule,',
		'iconfile' => 'EXT:e_annuaires/Resources/Public/Icons/tx_eannuaires_domain_model_fiche.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, intitule, typemandat, site',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, intitule, typemandat, site'),
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
				'foreign_table' => 'tx_eannuaires_domain_model_groupepolitique',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_groupepolitique.pid=###CURRENT_PID### AND tx_eannuaires_domain_model_groupepolitique.sys_language_uid IN (-1,0)',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'crdate' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $locallang_path.'tx_eannuaires_domain_model_groupepolitique.crdate',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
			),
		),
		'tstamp' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $locallang_path.'tx_eannuaires_domain_model_groupepolitique.tstamp',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
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
		'intitule' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_groupepolitique.intitule',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'typemandat' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_groupepolitique.typeMandat',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
                'items' => [
                    ['', 0],
                ],
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemTypeMandat'
			),
		),
		'site' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_groupepolitique.site',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
			'wizards' => array(
				'_PADDING' => 2,
				'link' => array(
					'type' => 'popup',
					'title' => 'Link',
					'icon' => 'link_popup.gif',
					'module' => array(
						'name' => 'wizard_element_browser',
						'urlParameters' => array(
							'mode' => 'wizard'
						)
					),
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				)
			)
		),
    ),
);

return $tx_eannuaires_domain_model_groupepolitique;
?>