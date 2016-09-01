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
class Mandat extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * typemandat
	 *
	 * @var \string
	 */
	protected $typemandat;

	/**
	 * communemandat
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche>
	 */
	protected $communemandat;

	/**
	 * groupepolitique
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Groupepolitique>
	 */
	protected $groupepolitique;

	/**
	 * circonscription
	 *
	 * @var \string
	 */
	protected $circonscription ;

	/**
	 * siteinternet
	 *
	 * @var \string
	 */
	protected $siteinternet;

	/**
	 * nomepci
	 *
	 * @var \string
	 */
	protected $nomepci;

	/**
	 * canton
	 *
	 * @var \string
	 */
	protected $canton;

	/**
	 * fonctions
	 *
	 * @var \string
	 */
	protected $fonctions;

	/**
	 * delegation
	 *
	 * @var \string
	 */
	protected $delegation;

	/**
	 * fonctionautre
	 *
	 * @var \string
	 */
	protected $fonctionautre;

	/**
	 * commissions
	 *
	 * @var \string
	 */
	protected $commissions;

	/**
	 * representations
	 *
	 * @var \string
	 */
	protected $representations;

        
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
		$this->groupepolitique = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();		
		$this->communemandat = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	 * Returns the communemandat
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $communemandat
	 */
	public function getCommunemandat() {
		return $this->communemandat;
	}

	/**
	 * Sets the communemandat
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $communemandat
	 * @return void
	 */
	public function setCommunemandat($communemandat) {
		$this->communemandat = $communemandat;
	}

	/**
	 * Returns the groupepolitique
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\groupepolitique> $groupepolitique
	 */
	public function getGroupepolitique() {
		return $this->groupepolitique;
	}

	/**
	 * Sets the groupepolitique
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\groupepolitique> $groupepolitique
	 * @return void
	 */
	public function setGroupepolitique($groupepolitique) {
		$this->groupepolitique = $groupepolitique;
	}

	/**
	 * Returns the circonscription
	 *
	 * @return \string $circonscription
	 */
	public function getCirconscription() {
		return $this->circonscription;
	}

	/**
	 * Sets the circonscription
	 *
	 * @param \string $circonscription
	 * @return void
	 */
	public function setCirconscription($circonscription) {
		$this->circonscription = $circonscription;
	
	}
	
	/**
	 * Returns the siteinternet
	 *
	 * @return \string $siteinternet
	 */
	public function getSiteinternet() {
		return $this->siteinternet;
	}

	/**
	 * Sets the siteinternet
	 *
	 * @param \string $siteinternet
	 * @return void
	 */
	public function setSiteinternet($siteinternet) {
		$this->siteinternet = $siteinternet;
	}

	/**
	 * Returns the nomepci
	 *
	 * @return \string $nomepci
	 */
	public function getNomepci() {
		return $this->nomepci;
	}

	/**
	 * Sets the nomepci
	 *
	 * @param \string $nomepci
	 * @return void
	 */
	public function setNomepci($nomepci) {
		$this->nomepci = $nomepci;
	}

	/**
	 * Returns the canton
	 *
	 * @return \string $canton
	 */
	public function getCanton() {
		return $this->canton;
	}

	/**
	 * Sets the canton
	 *
	 * @param \string $canton
	 * @return void
	 */
	public function setCanton($canton) {
		$this->canton = $canton;
	}

	/**
	 * Returns the fonctions
	 *
	 * @return \string $fonctions
	 */
	public function getFonctions() {
		return $this->fonctions;
	}

	/**
	 * Sets the fonctions
	 *
	 * @param \string $fonctions
	 * @return void
	 */
	public function setFonctions($fonctions) {
		$this->fonctions = $fonctions;
	}

	/**
	 * Returns the fonctionautre
	 *
	 * @return \string $fonctionautre
	 */
	public function getFonctionautre() {
		return $this->fonctionautre;
	}

	/**
	 * Sets the fonctionautre
	 *
	 * @param \string $fonctionautre
	 * @return void
	 */
	public function setFonctionautre($fonctionautre) {
		$this->fonctionautre = $fonctionautre;
	}

	/**
	 * Returns the delegation
	 *
	 * @return \string $delegation
	 */
	public function getDelegation() {
		return $this->delegation;
	}

	/**
	 * Sets the delegation
	 *
	 * @param \string $delegation
	 * @return void
	 */
	public function setDelegation($delegation) {
		$this->delegation = $delegation;
	}

	/**
	 * Returns the commissions
	 *
	 * @return \string $commissions
	 */
	public function getCommissions() {
		return $this->commissions;
	}

	/**
	 * Sets the commissions
	 *
	 * @param \string $commissions
	 * @return void
	 */
	public function setCommissions($commissions) {
		$this->commissions = $commissions;
	}

	/**
	 * Returns the representations
	 *
	 * @return \string $representations
	 */
	public function getRepresentations() {
		return $this->representations;
	}

	/**
	 * Sets the representations
	 *
	 * @param \string $representations
	 * @return void
	 */
	public function setRepresentations($representations) {
		$this->representations = $representations;
	}
}
?>