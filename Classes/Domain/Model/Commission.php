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
class Commission extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * titre
	 *
	 * @var \string
	 */
	protected $titre;

	/**
	 * president
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche>
	 */
	protected $president;

	/**
	 * membres
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche>
	 */
	protected $membres;

	/**
	 * attributions
	 *
	 * @var \string
	 */
	protected $attributions;
	

	/**
	 * sorting
	 *
	 * @var integer
	 */
	protected $sorting = 0;
	
    
	/**
	 * __construct
	 *
	 * @return Diapos
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
		$this->president = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->membres = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the titre
	 *
	 * @return \string $titre
	 */
	public function getTitre() {
		return $this->titre;
	}

	/**
	 * Sets the titre
	 *
	 * @param \string $titre
	 * @return void
	 */
	public function setTitre($titre) {
		$this->titre = $titre;
	}
	
	/**
	 * Returns the president
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $president
	 */
	public function getPresident() {
		return $this->president;
	}

	/**
	 * Sets the president
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $president
	 * @return void
	 */
	public function setPresident(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $president) {
		$this->president = $president;
	}
	
	/**
	 * Returns the membres
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $membres
	 */
	public function getMembres() {
		return $this->membres;
	}

	/**
	 * Sets the membres
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $membres
	 * @return void
	 */
	public function setMembres(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $membres) {
		$this->membres = $membres;
	}

	/**
	 * Returns the attributions
	 *
	 * @return \string $attributions
	 */
	public function getAttributions() {
		return $this->attributions;
	}

	/**
	 * Sets the attributions
	 *
	 * @param \string $attributions
	 * @return void
	 */
	public function setAttributions($attributions) {
		$this->attributions = $attributions;
	}

	/**
	 * Returns the sorting
	 *
	 * @return integer $sorting
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * Sets the sorting
	 *
	 * @param integer $sorting
	 * @return void
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}
}
?>