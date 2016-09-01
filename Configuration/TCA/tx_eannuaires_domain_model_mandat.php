<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

//type,commune,groupe_politique_cr,circonscription,groupe_politique_depute,siteinternet,groupe_politique_senateur,siteinternet,nomepci,groupe_politique_epci,siteinternet,canton,fonctions,delegation,fonctionautre,groupe_politique_cg,commissions,representations

$locallang_path = 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:';

$tx_eannuaires_domain_model_mandat = array(
	'ctrl' =>  array(
		'title'	=> 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_mandat',
		'label' => 'typemandat', 
		'label_userFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_labelMandats',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		
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
		'searchFields' => 'site_depute,site_senateur,site_epci,delegation,fonction_autre,commissions,representations,',
		'iconfile' => 'EXT:e_annuaires/Resources/Public/Icons/tx_eannuaires_domain_model_fiche.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,typemandat,commune,groupepolitique,circonscription,siteinternet,nomepci,canton,fonctions,delegation,fonctionautre,commissions,representations',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, canton, fonctions, delegation, fonctionautre, groupepolitique, commissions, representations'),
		'2' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, groupepolitique'),
		'3' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, circonscription, groupepolitique, siteinternet'),
		'4' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, communemandat'),
		'5' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, nomepci, groupepolitique, siteinternet'),
		'6' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, typemandat, groupepolitique, siteinternet'),
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
				'foreign_table' => 'tx_eannuaires_domain_model_mandat',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_mandat.pid=###CURRENT_PID### AND tx_eannuaires_domain_model_mandat.sys_language_uid IN (-1,0)',
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
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.crdate',
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
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.tstamp',
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
		'typemandat' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.typeMandat',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'minitems' => 0,
				'maxitems' => 1,
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemTypeMandat',
			),
		),
		'communemandat' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.communeMandat',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_eannuaires_domain_model_fiche', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.typeelement = ###PAGE_TSCONFIG_ID### ORDER BY tx_eannuaires_domain_model_fiche.title',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),
		'groupepolitique' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.groupepolitique',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_eannuaires_domain_model_groupepolitique', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_groupepolitique.hidden=0 AND tx_eannuaires_domain_model_groupepolitique.deleted=0 AND typemandat = ###REC_FIELD_typemandat### ORDER BY tx_eannuaires_domain_model_groupepolitique.intitule ',//AND tx_eannuaires_domain_model_groupepolitique.typemandat = ###PAGE_TSCONFIG_ID###',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),
		'circonscription' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.circonscription',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemCircLegislative',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),
		'siteinternet' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.siteInternet',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'nomepci' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.nomEpci',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_eannuaires_domain_model_fiche', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.hidden=0 AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.pid = ###PAGE_TSCONFIG_ID### ORDER BY tx_eannuaires_domain_model_fiche.title',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			)
		),
		'canton' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.canton',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemCanton',
				'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			)
		),
		'fonctions' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.fonctions',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemFonctions',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			)
		),
		'delegation' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.delegation',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'fonctionautre' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.fonctionAutre',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'commissions' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.commissions',
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
		),
		'representations' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_mandat.representations',
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
		),
    ),
);

return $tx_eannuaires_domain_model_mandat;
?>