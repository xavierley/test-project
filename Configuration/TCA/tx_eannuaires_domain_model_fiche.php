<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$locallang_path = 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:';

// On récupère le premier type de l'annuaire qui sera défini par défaut si pas de tsconfig
$typeAnnuaire = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', 'tx_eannuaires_domain_model_typeannuaire', 'deleted = 0 AND hidden = 0', '', 'uid ASC', 2);

$tx_eannuaires_domain_model_fiche = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_fiche',
		'label' => 'title',
		'label_alt' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
        'type' => 'typeelement',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,name,firstname,address,zipcode,bp,cell,phone,config,typeelement,lat,lng,picto,description,image,www,email,feuser,civility,office,categories,city,district,documents,',
		'iconfile' => 'EXT:e_annuaires/Resources/Public/Icons/tx_eannuaires_domain_model_fiche.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, name, firstname, intituleaddress, address, zipcode, bp, cell, phone, phonesecondaire, typeelement, description, image, www, email, mail, feuser, civility, office, categories, city, citylinked, district, documents, typeepci',
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_eannuaires_domain_model_fiche',
                'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.pid=###CURRENT_PID### AND tx_eannuaires_domain_model_fiche.sys_language_uid IN (-1,0)',
                'showIconTable' => false
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'cruser_id' => [
            'label' => 'cruser_id',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'sorting' => [
            'label' => 'sorting',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'max' => 20,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'max' => 20,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
		'title' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'name' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'firstname' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.firstname',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'intituleaddress' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.intituleAddress',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.address',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'zipcode' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.zipcode',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'bp' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.bp',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'cell' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.cell',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'phone' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.phone',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'phonesecondaire' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.phoneSecondaire',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max' => '256',
				'eval' => 'trim',
			),
		),
		'typeelement' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.typeelement',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectSingle',
				'minitems' => 1,
				'default' => $typeAnnuaire[1]['uid'],
				'foreign_table' => 'tx_eannuaires_domain_model_typeannuaire',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_typeannuaire.hidden=0 AND tx_eannuaires_domain_model_typeannuaire.deleted=0'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.description',
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
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_image.media',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'image',
				array(
                    'appearance' => array(
                        'collapseAll' => 1
                    ),
					'foreign_match_fields' => array(
						'fieldname' => 'image'
					),
                ),
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
		),
		'media' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_image.media',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'media',
				array(
                    'appearance' => array(
                        'collapseAll' => 1
                    ),
					'foreign_match_fields' => array(
						'fieldname' => 'media'
					),
                ),
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'].',flv'
			),
		),
		'www' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.www',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'email' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'mail' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.mail',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'feuser' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.feuser',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_table' => 'fe_users',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            )
		),
		'civility' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.civility',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Mr', 0),
					array('Mlle', 1),
					array('Mme', 2),
				),
				'eval' => ''
			),
		),
		'office' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.office',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:e_annuaires/Resources/Private/Language/locallang_db.xlf:tx_eannuaires_domain_model_fiche.categories',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectTree',
				'treeConfig' => array(
					'parentField' => 'parent',
					'appearance' => array(
						'showHeader' => TRUE,
						'allowRecursiveMode' => TRUE,
						'expandAll' => TRUE,
						'maxLevels' => 99,
					),
				),
				'MM' => 'sys_category_record_mm',
				'MM_match_fields' => array(
					'fieldname' => 'categories',
					'tablenames' => 'tx_eannuaires_domain_model_fiche',
				),
				'MM_opposite_field' => 'items',
				'foreign_table' => 'sys_category',
				'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
				'minitems' => 0,
				'maxitems' => 99,
				'size' => 10,
				'autoSizeMax' => 20,
				'appearance' => array(
					'showHeader' => TRUE,
					'allowRecursiveMode' => TRUE,
					'expandAll' => TRUE,
					'maxLevels' => 99,
				),
			),
		),
		'city' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.city',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_enews_domain_model_commune',
				'foreign_table_where' => 'AND tx_enews_domain_model_commune.hidden=0 AND tx_enews_domain_model_commune.deleted=0',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),
		'citylinked' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.cityLinked',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_enews_domain_model_commune',
				'foreign_table_where' => 'AND tx_enews_domain_model_commune.hidden=0 AND tx_enews_domain_model_commune.deleted=0',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                    '_VERTICAL' => 1,
                    '_PADDING' => 4,
                ),
			),
		),
		'district' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.district',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_eannuaires_domain_model_district',
				'minitems' => 0,
			),
		),
		'documents' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.documents',
			'config' => array(
                'type' => 'inline',    
                'foreign_table' => 'tx_eannuaires_domain_model_documents',
                'minitems' => 0,
                'maxitems' => 15,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
			),
		),
        'liens' => array(
            'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.liens',
            'config' => array(
                'type' => 'inline',    
                'foreign_table' => 'tx_eannuaires_domain_model_lien',
                'minitems' => 0,
                'maxitems' => 15,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'address2' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.address2',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'cedex' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.cedex',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'fax' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.fax',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'faxsecondaire' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.faxSecondaire',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'country' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.country',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'canton' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.canton',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size'=> 5,
				'minitems' => 0,
				'maxitems' => 10,
				'foreign_table' => 'tx_eannuaires_domain_model_fiche',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.hidden=0 AND tx_eannuaires_domain_model_fiche.deleted=0',
			),
        ),
        'commission' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.commission',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_eannuaires_domain_model_commission',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_commission.hidden=0 AND tx_eannuaires_domain_model_commission.deleted=0',
			),
        ),
        'actions' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.actions',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'minitems' => 0,
				'maxitems' => 100,
				'size' => 5,
				'foreign_table' => 'tx_eannuaires_domain_model_actions',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_actions.hidden=0 AND tx_eannuaires_domain_model_actions.deleted=0',
			),
        ),
        'production' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.production',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
		'ouvert' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.ouvert',
			'config' => array(
				'type' => 'check',
			),
		),
		'ouvertcomment' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.ouvertcomment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 10,
				'eval' => 'trim'
			),
		),
		'beneficiaire' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.beneficiaire',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 10,
				'eval' => 'trim'
			),
		),
        'objet' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.objet',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 100,
				'foreign_table' => 'tx_eannuaires_domain_model_objet',
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_objet.hidden=0 AND tx_eannuaires_domain_model_objet.deleted=0',
			),
        ),
		'enjeux' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.enjeux',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 10,
				'eval' => 'trim'
			),
		),
        'uploadimage' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.uploadimage',
			'config' => array(
                'type' => 'inline',    
                'foreign_table' => 'tx_eannuaires_domain_model_image',
                'minitems' => 0,
                'maxitems' => 15,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
			),
        ),
		'contacts' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.contacts',
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
        'linkcontact' => array(
            'exclude' => 1,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.linkcontact',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'foreign_table' => 'pages',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 100,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            )
        ),        
		'hebergementtemp' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.hebergementtemp',
			'config' => array(
				'type' => 'check',
			),
		),
		'accueiljour' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.accueiljour',
			'config' => array(
				'type' => 'check',
			),
		),
        'uniteterritoriale' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.uniteterritoriale',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'cantonstexte' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.cantonstexte',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'intercommunalite' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.intercommunalite',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
        ),
        'datefiche' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.datefiche',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => time(),
			),
        ),
        'typeepci' => array( 
        	'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.epci',
			'config' => array(
				'foreign_table' => 'tx_eannuaires_domain_model_fiche', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.hidden=0 AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.pid = "###PAGE_TSCONFIG_ID###" ', 
				'maxitems' => '100',
				'minitems' => '0',
				'size' => '5',
				'renderType' => 'selectMultipleSideBySide',
				'type' => 'select'
			)
		),
		'mandats' => array( 
        	'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.mandats',
            'config' => array(
                'type' => 'inline',    
                'foreign_table' => 'tx_eannuaires_domain_model_mandat',
                'foreign_table_where' => 'AND tx_eannuaires_domain_model_mandat.hidden=0 AND tx_eannuaires_domain_model_mandat.deleted=0 ORDER BY tx_eannuaires_domain_model_mandat.sorting', 
                'minitems' => 0,
                'maxitems' => 15,
                'appearance' => array(
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
		),
		'typehandicap' => array( 
        	'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.typeHandicap',
            'config' => array(
                'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemHandicap',
				'maxitems' => '50',
				'minitems' => '0',
				'size' => '5',
				'renderType' => 'selectMultipleSideBySide',
				'type' => 'select'
            )
		),
		'contrat' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.contrat',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'minitems' => '0',
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemContrat'
			)
        ),
		'population' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.population',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max' => '256',
				'eval' => 'trim'
			)
        ),
		'cdps' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.cdps',
			'config' => array(
			    'foreign_table' => 'tx_eannuaires_domain_model_fiche', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.pid = "###PAGE_TSCONFIG_ID###" ', 
				'maxitems' => '100',
				'minitems' => '0',
				'size' => '5',
				'renderType' => 'selectMultipleSideBySide',
				'type' => 'select'
			)
        ),
		'mds' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.mds',
			'config' => array(
			    'foreign_table' => 'tx_eannuaires_domain_model_fiche', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_fiche.hidden=0 AND tx_eannuaires_domain_model_fiche.deleted=0 AND tx_eannuaires_domain_model_fiche.pid = "###PAGE_TSCONFIG_ID###" ', 
				'maxitems' => '100',
				'minitems' => '0',
				'size' => '5',
				'renderType' => 'selectMultipleSideBySide',
				'type' => 'select'
			)
        ),
		'pack' => array( 
        	'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.pack',
            'config' => array(
				'foreign_table' => 'tx_eannuaires_domain_model_pack', 
				'foreign_table_where' => 'AND tx_eannuaires_domain_model_pack.hidden=0 AND tx_eannuaires_domain_model_pack.deleted=0 ', 
				'minitems' => '0',
				'renderType' => 'selectSingle',
				'type' => 'select'
            ),
		),
		'partipolitique' => array( 
        	'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.feuser',
            'config' => array(
				'itemsProcFunc' => 'Emagineurs\EAnnuaires\Hooks\ItemsProcFunc->user_itemPartiPolitique', 
				'maxitems' => '100',
				'minitems' => '0',
				'size' => '5',
				'renderType' => 'selectMultipleSideBySide',
				'type' => 'select'
            ),
		),
		'latsecondaire' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.latsecondaire',
			'config'=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'map' => array(
						'type' => 'popup',
						'title' => 'Choisir centre',
						'icon' => 'i/domain.gif',
						'module' => array(
							'name' => 'choosePoi'
						),
						'JSopenParams' => 'width=800,height=600,status=0,menubar=0,scrollbars=0'
					)
				)
			)
		),
		'lngsecondaire' => array(
			'exclude' => 0,
			'label' => $locallang_path.'tx_eannuaires_domain_model_fiche.lngsecondaire',
			'config'=>array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'map' => array(
						'type' => 'popup',
						'title' => 'Choisir centre',
						'icon' => 'i/domain.gif',
						'module' => array(
							'name' => 'choosePoi'
						),
						'JSopenParams' => 'width=800,height=600,status=0,menubar=0,scrollbars=0'
					)
				)
				
			)
		),
    )
);

$confExtTplAnnuaires = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);

$typeCanton = $confExtTplAnnuaires['typeCanton'];
if($typeCanton){
    $tx_eannuaires_domain_model_fiche['columns']['canton']['config']['foreign_table_where'] .= ' AND typeelement='.$typeCanton;
}

// On instancie un sys_registry
$registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Registry');

// On récupère la conf depuis la table des registres
$confAnnuaires = $registry->get('tx_eannuaires','confAnnuaires');
$locallangConfAnnuaires = $registry->get('tx_eannuaires','locallangPath');

if(is_array($confAnnuaires) && !empty($confAnnuaires)){
	$confExtTplAnnuaires['confType'] = $confAnnuaires;
}

if(trim($locallangConfAnnuaires) != ''){
	$locallang_path = 'LLL:'.$locallangConfAnnuaires.':';	
}

// TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($confExtTplAnnuaires['confType'],'TEST');
// die;
if(is_array($confExtTplAnnuaires['confType'])){
						
	$page = (empty($_GET['id']))?0:intval($_GET['id']);
	
	// $tsconfig = TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($page); 
	$idTypePage = TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages',$page,'typeannuaire');
	
	// $typeTsConfig = $tsconfig['annuaires.']['type'];
	$typeTsConfig = $idTypePage['typeannuaire'];
        
    foreach($confExtTplAnnuaires['confType'] as $idType => $confType){
        $fieldListeArray = array();
        if(is_array($confType)){
            foreach($confType as $field => $infos){
                if($field != 'titleType'){   
					if($infos['value']){   
						if($infos['sorting']){
							$fieldListeArray[$infos['sorting']] = ($infos['newTab'] != '') ? '--div--;'.$locallang_path.$infos['newTab'].',' : '';
							
							$fieldListeArray[$infos['sorting']] .= $infos['value'];
							if($infos['label']){
								$fieldListeArray[$infos['sorting']] .= ';'.$locallang_path.$infos['label'];
							}
						} else {
							$count = count($fieldListeArray)+1;
							$fieldListeArray[$count] = ($infos['newTab'] != '') ? '--div--;'.$locallang_path.$infos['newTab'].',' : '';
							$fieldListeArray[$count] .= $infos['value'];
						}
						
						if($infos['label'] && $idType == $typeTsConfig){                            
							$tx_eannuaires_domain_model_fiche['columns'][$infos['value']]['label'] = $locallang_path.$infos['label'];
						}
					}
                }
            }
            
            ksort($fieldListeArray);
            $fieldListeArray = array_unique($fieldListeArray);
            $fieldListe = implode(',',$fieldListeArray);
            
            if(in_array('typelement',$fieldListeArray)){
                $defaultFields = '';
            } else {
                $defaultFields = 'typeelement,';
            }
            
            if(!in_array('hidden',$fieldListeArray)){
                $defaultFields .= 'hidden,';
            }
            
            $tx_eannuaires_domain_model_fiche['types'][$idType]['showitem'] = $defaultFields.$fieldListe;
        }
    }
} 

$tx_eannuaires_domain_model_fiche['types'][0]['showitem'] = $defaultFields.'title';

return $tx_eannuaires_domain_model_fiche;
?>