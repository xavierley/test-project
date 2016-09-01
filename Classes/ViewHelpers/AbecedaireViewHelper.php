<?php
namespace Emagineurs\EAnnuaires\ViewHelpers;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ian SEBBAGH <isebbagh@e-magineurs.com>, E-magineurs
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
 * @package e_pdl_patrimoine
 * @subpackage ViewHelpers
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
class AbecedaireViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{

	/**
	* @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	*/
	protected $configurationManager;

	/**
	* @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	* @return void
	*/
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	* @return void
	*/
	protected function getConfTs() {
		$frameworkConfiguration[0] = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$frameworkConfiguration[1] = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

		return $frameworkConfiguration;
	}
    /**
     * @param mixed $list
     * @return string abecedaire
     */
    public function render($list){
        $content = '';
        $confTs = $this->getConfTs();
        $settings = $confTs[0];
        
        $alphabet = array(
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
            'F' => 'F',
            'G' => 'G',
            'H' => 'H',
            'I' => 'I',
            'J' => 'J',
            'K' => 'K',
            'L' => 'L',
            'M' => 'M',
            'N' => 'N',
            'O' => 'O',
            'P' => 'P',
            'Q' => 'Q',
            'R' => 'R',
            'S' => 'S',
            'T' => 'T',
            'U' => 'U',
            'V' => 'V',
            'W' => 'W',
            'X' => 'X',
            'Y' => 'Y',
            'Z' => 'Z'
        );
        $linkAlphabet = array(); 
        
        $cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
                
        $fichesLetters = array_keys($list);
        $sitePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                
        // foreach($list as $letter => $item){
        foreach($alphabet as $letter){
            
            if(in_array($letter, $fichesLetters)){
                $conf = array();
                $conf['parameter'] = $GLOBALS['TSFE']->id;
                
                $confArray = $_GET;
				if($settings['one_letter'] && $settings['one_letter'] == '1'){
					$confArray['char'] = $letter;
				}
                unset($confArray['id']);
				$confArray['char'] = $letter;
                
                // $conf['returnLast'] = 'url';
				
				if(!$settings['one_letter'] || $settings['one_letter'] != '1'){
                    $conf['section'] = $letter;
				} else {
                    $conf['additionalParams'] = \TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('', $confArray, '', TRUE);
                }
                
                $url = $cObj->typolink($letter,$conf);
                
                // $linkAlphabet[$letter] = '<a href="'.$url.'">'.$letter.'</a>';
                $linkAlphabet[$letter] = $url;
            } else {
                $linkAlphabet[] = $letter;
            }

            // if(is_array($fieldToTest)){
                // $valueToTest = $fieldToTest[0];
                // $actualField = $fieldToTest[1];

                // $firstLetter = strtoupper(substr($item->$valueToTest()->current()->$actualField(),0,1));
            // } else {
                // $firstLetter = strtoupper(substr($item->$fieldToTest(),0,1));
            // }
            
            // if(in_array($firstLetter,$alphabet)){
                // $sitePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                // $conf = array();
                
                // $conf['parameter'] = $GLOBALS['TSFE']->id;
                
                // $confArray = $_GET;
				// if($settings['one_letter'] && $settings['one_letter'] == '1'){
					// $confArray['char'] = $firstLetter;
				// }
                // unset($confArray['id']);
				// $confArray['char'] = $firstLetter;
                
                // $conf['additionalParams'] = \TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('', $confArray, '', TRUE);
                // $conf['returnLast'] = 'url';
				
				// if($settings['one_letter'] && $settings['one_letter'] == '1'){
					// $confArray['char'] = $firstLetter;
					// $url = $cObj->typolink($firstLetter,$conf);
					// $linkAlphabet[$firstLetter] = '<a href="'.$url.'">'.$firstLetter.'</a>';
				// } else {
					// $url = $cObj->typolink($firstLetter,$conf).'#'.$firstLetter;
					// $linkAlphabet[$firstLetter] = '<a href="'.$url.'">'.$firstLetter.'</a>';				
				// }
            // }            
            
        }       
        return $linkAlphabet;
    }    
}

?>