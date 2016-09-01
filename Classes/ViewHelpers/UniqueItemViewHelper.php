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
 
class UniqueItemViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{

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
		
    }
    
}

?>