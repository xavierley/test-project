<?php
namespace Emagineurs\EAnnuaires\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 
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
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Groupepolitique extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {


	/**
	 * intitule
	 *
	 * @var \string
	 */
	protected $intitule;
        
	/**
	 * typemandat
	 *
	 * @var \string
	 */
	protected $typemandat;
    
    /**
	 * site
	 *
	 * @var \string
	 */
	protected $site;
    

	/**
	 * __construct
	 *
	 * @return Fiche
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
	}

	/**
	 * Returns the intitule
	 *
	 * @return \string $intitule
	 */
	public function getIntitule() {
		return $this->intitule;
	}

	/**
	 * Sets the intitule
	 *
	 * @param \string $intitule
	 * @return void
	 */
	public function setIntitule($intitule) {
		$this->intitule = $intitule;
	}


	/**
	 * Returns the typemandat
	 *
	 * @return \string $typemandat
	 */
	public function getTypemandat() {
		return $this->typemandat;
	}

	/**
	 * Sets the typemandat
	 *
	 * @param \string $typemandat
	 * @return void
	 */
	public function setTypemandat($typemandat) {
		$this->typemandat = $typemandat;
	}

	/**
	 * Returns the site
	 *
	 * @return \string $site
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * Sets the site
	 *
	 * @param \string $site
	 * @return void
	 */
	public function setSite($site) {
		$this->site = $site;
	}
}
?>