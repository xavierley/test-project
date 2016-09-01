<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tx_eannuaires_domain_model_commission = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_commission',
		'label' => 'titre',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'titre,',
		'iconfile' => 'EXT:e_annuaires/Resources/Public/Icons/tx_eannuaires_domain_model_commission.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, titre, president, membres, attributions',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, titre, president, membres, attributions'),
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
				'foreign_table' => 'tx_eannuaires_domain_model_commission',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_commission.pid=###CURRENT_PID### AND tx_eannuaires_domain_model_commission.sys_language_uid IN (-1,0)',
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
		'titre' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_commission.titre',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),			
		'president' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_commission.president',
			'config' => array(
				'type' => 'select',
				'minitems' => 0,
				'maxitems' => 1,
				'foreign_table' => 'tx_eannuaires_domain_model_fiche',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.hidden=0',
			),
		),			
		'membres' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_commission.membres',
			'config' => array(
				'type' => 'select',
				'minitems' => 0,
				'maxitems' => 100,
				'size' => 5,
				'foreign_table' => 'tx_eannuaires_domain_model_fiche',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.hidden=0',
			),
		),
		'attributions' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_commission.attributions',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte'
						),
						'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
        )
	),
);


$confExtTplAnnuaires = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
$typeCommission = $confExtTplAnnuaires['typeCommission'];
if($typeCommission){
    $tx_eannuaires_domain_model_commission['columns']['membres']['config']['foreign_table_where'] .= ' AND typeelement IN ('.$typeCanton.')';
}

return $tx_eannuaires_domain_model_commission;
?>