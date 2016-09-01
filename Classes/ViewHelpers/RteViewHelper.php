<?php
namespace Emagineurs\EAnnuaires\ViewHelpers;

	class RteViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{

		public $RTEcounter = 0;
		public $strEntryField;
		public $thePidValue;
		public $preJsRTE;
		public $postJsRTE;
		public $submitJsRTE;
		public $PA = array();

		/**
		 * Return RTE
		 *
		 * @param	string	$champ		Field name
		 * @param	string	$namePrefix	Name prefix (tx_ext_pi1[object])
		 * @param	boolean	$isLast		Is last flag (generate JavaScript only for the last RTE)
		 * @param	boolean	$numRTE		Is first flag (generate JavaScript only for the last RTE)
		 * @param	string	$valueRTE		Any value
		 * @param	string	$rteNumber		allow to create an instance different of tx_rtehtmlarea_pi2 each time the viewhelper is used 
		 * @param	string	$height		height of the RTE
		 * @param	string	$postjs		display post js
		 * @return 	string	Generated RTE content
		 */
		public function render($champ = '', $tsConfigPath = '', $isLast = 0, $numRTE = 0, $valueRTE = '', $rteNumber = 'RTEObj', $height = '200', $postjs = 0) {		
			$nodeFactory = \TYPO3\CMS\Core\Utility\generalUtility::makeInstance('TYPO3\CMS\Backend\Form\NodeFactory');
		
			$champ = 'description';
			$data = array( 
				'command' => '',
				// 'tableName' => 'tx_eannuaires_domain_model_fiche',
				'tableName' => '',
				'vanillaUid' => '',
				'returnUrl' => '',
				'recordTitle' => '',
				'parentPageRow' => array(),
				'neighborRow' => NULL,
				'databaseRow' => array(),
				'effectivePid' => '',
				'rootline' => array(),
				'userPermissionOnPage' => '',
				'userTsConfig' => array(),
				'pageTsConfig' => array(),
				'vanillaParentPageTca' => array(),
				'systemLanguageRows' => array(),
				'pageLanguageOverlayRows' => array(),
				'defaultLanguageRow' => NULL,
				'defaultLanguageDiffRow' => NULL,
				'additionalLanguageRows' => array(),
				'recordTypeValue' => '',
				'processedTca' => array(),
				'columnsToProcess' => array(),
				'disabledWizards' => FALSE,
				'flexParentDatabaseRow' => array(),
				'inlineExpandCollapseStateArray' => array(),
				'inlineFirstPid' => '',
				'inlineParentConfig' => array(),
				'isInlineChild' => FALSE,
				'isInlineChildExpanded' => FALSE,
				'isInlineAjaxOpeningContext' => FALSE,
				'inlineParentUid' => '',
				'inlineParentTableName' => '',
				'inlineParentFieldName' => '',
				'inlineTopMostParentUid' => '',
				'inlineTopMostParentTableName' => '',
				'inlineTopMostParentFieldName' => '',
				'isOnSymmetricSide' => FALSE,
				'inlineChildChildUid' => NULL,
				'isInlineDefaultLanguageRecordInLocalizedParentContext' => FALSE,
				'inlineResolveExistingChildren' => TRUE,
				'inlineCompileExistingChildren' => TRUE,
				'elementBaseName' => '',
				'flexFormFieldIdentifierPrefix' => 'ID',
				'tabAndInlineStack' => array(),
				'inlineData' => array(),
				'inlineStructure' => array(),
				'overrideValues' => array(),
				'renderType' => 'text',
				'fieldsArray' => array(),
				'fieldName' => '',
				'parameterArray' => array(
					'fieldConf' => $GLOBALS['TCA']['tx_eannuaires_domain_model_fiche']['columns'][$champ]
				),
				'tsConfigPath' => $tsConfigPath,
			);
		/*
		*/
			
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($data['parameterArray'],'TEST data');
		
			$this->pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
			$this->pageRenderer->loadJquery();
		
			$rteClass = \TYPO3\CMS\Core\Utility\generalUtility::makeInstance('Emagineurs\EAnnuaires\Utility\FrontendRte',$nodeFactory,$data);
			
			$renderRteArray = $rteClass->render();

			// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($renderRteArray,'TEST renderRteArray');
			
			$content = '';
			
			$postJsRte = '<script type="text/javascript">' . implode(chr(10), $renderRteArray['additionalJavaScriptPost']) . '</script>';
			$submitJsRte = '<script type="text/javascript">function rteMove'.$numRTE.'(){' . implode(';', $renderRteArray['additionalJavaScriptSubmit']) . '}</script>';
						
			$content = $renderRteArray['html'] . $postJsRte . $submitJsRte;
			
			return $content;
			
			
/*
			
			// $this->preJsRTE = $this->additionalJS_initial . '<script type="text/javascript">' . implode(chr(10), $this->additionalJS_pre) . '</script>';
			$this->postJsRTE = '<script type="text/javascript">' . implode(chr(10), $this->additionalJS_post) . '</script>';
			
			// Les différentes fonctions rteMove qui sont générés par le viewhelper sont à placé dans un attribut onsubmit du formulaire
			$this->submitJsRTE = '<script type="text/javascript">function rteMove'.$numRTE.'(){' . implode(';', $this->additionalJS_submit) . '}</script>';
			
			// n.b : tel quel, si on a 2 RTE le 2e a une hauteur buggé, elle est corrigeable en CSS.
			// return $this->preJsRTE . $RTEItem . $this->postJsRTE . $this->submitJsRTE;
			return $RTEItem . $this->postJsRTE . $this->submitJsRTE;

			------------------------
			
			// config
			require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('rtehtmlarea') . 'pi2/class.tx_rtehtmlarea_pi2.php');
			if (!$this->$rteNumber) {
				$this->$rteNumber = t3lib_div::makeInstance('tx_rtehtmlarea_pi2');
			}
			
			$this->RTEcounter++;
			$this->strEntryField = $nameRte;
			$this->PA['itemFormElName'] = $namePrefix . '['.$nameRte.']';
			$this->PA['itemFormElValue'] = $valueRTE;
			$this->thePidValue = $GLOBALS['TSFE']->id;
			// $this->thisConfig['RTEHeightOverride'] = $height;
						
			$this->registerFieldNameForFormTokenGeneration($namePrefix .$rteNumber. '[_TRANSFORM_rte]');
			$this->registerFieldNameForFormTokenGeneration($namePrefix .$rteNumber. '[rte]');
			
			// TEST completement degueulasse
			echo '<style>.htmlarea-body iframe{min-height: 200px !important;}</style>';
			
			// let's go
			$RTEItem = $this->$rteNumber->drawRTE(
				$this,
				'',
				$this->strEntryField,
				$row = array(),
				$this->PA,
				$this->specConf,
				$this->thisConfig,
				$this->RTEtypeVal,
				'',
				$this->thePidValue
			);
*/	
		}
		
	}

?>