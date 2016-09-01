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
class City extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	
	/**
	 * titre
	 *
	 * @var \string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $nom;
	 
	/**
	 * @var string
	 */
	protected $codePostal = 0;	
	
	/**
	 * image
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $image = NULL;
   
	/**
	 * intercommunalite
	 *
	 * @var string
	 */
	protected $intercommunalite;

	/**
	 * cantons
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche>
	 */
	protected $cantons;
   
	/**
	 * maire
	 *
	 * @var string
	 */
	protected $maire;
   
	/**
	 * population
	 *
	 * @var string
	 */
	protected $population;
   
	/**
	 * adresseMairie
	 *
	 * @var string
	 */
	protected $adresseMairie;
   
	/**
	 * telephone
	 *
	 * @var string
	 */
	protected $telephone;
   
	/**
	 * fax
	 *
	 * @var string
	 */
	protected $fax;
   
	/**
	 * courriel
	 *
	 * @var string
	 */
	protected $courriel;
   
	/**
	 * siteInternet
	 *
	 * @var string
	 */
	protected $siteInternet;
   
	/**
	 * facebook
	 *
	 * @var string
	 */
	protected $facebook;
   
	/**
	 * twitter
	 *
	 * @var string
	 */
	protected $twitter;
   
	/**
	 * autreLien
	 *
	 * @var string
	 */
	protected $autreLien;
	
	
	/********************************************************************************************/
	/********************************************************************************************/
	/******************************    METHODES   ************************************/
	/********************************************************************************************/
	/********************************************************************************************/

	/**
	 * Returns the titre -> ancien champ, au cas ou il reste des appels dans les templates
	 *
	 * @return string $title
	 */
	public function getTitre() {
		return $this->title;
	}

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Get lieu
	 *
	 * @return string
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom nom
	 * @return void
	 */
	public function setNom($nom) {
		$this->nom= $nom;
	}

	/**
	 * Returns the codePostal
	 *
	 * @return string $codePostal
	 */
	public function getCodePostal() {
		return $this->codePostal;
	}

	/**
	 * Sets the codePostal
	 *
	 * @param string $codePostal
	 * @return void
	 */
	public function setCodePostal($codePostal) {
		$this->codePostal = $codePostal;
	}

	/**
	 * Returns the image
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
	 * @return void
	 */
	public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image) {
		$this->image = $image;
	}
    
	/**
	 * Returns the intercommunalite
	 *
	 * @return string $intercommunalite
	 */
	public function getIntercommunalite() {
		return $this->intercommunalite;
	}

	/**
	 * Sets the intercommunalite
	 *
	 * @param string $intercommunalite
	 * @return void
	 */
	public function setIntercommunalite($intercommunalite) {
		$this->intercommunalite = $intercommunalite;
	}
	
	/**
	 * Returns the cantons
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $cantons
	 */
	public function getCantons() {
		return $this->cantons;
	}


	/**
	 * Sets the categorie
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Emagineurs\EAnnuaires\Domain\Model\Fiche> $cantons
	 * @return void
	 */
	public function setCantons(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $cantons) {
		$this->cantons = $cantons;
	}
    
	/**
	 * Returns the maire
	 *
	 * @return string $maire
	 */
	public function getMaire() {
		return $this->maire;
	}

	/**
	 * Sets the maire
	 *
	 * @param string $maire
	 * @return void
	 */
	public function setMaire($maire) {
		$this->maire = $maire;
	}
    
	/**
	 * Returns the population
	 *
	 * @return string $population
	 */
	public function getPopulation() {
		return $this->population;
	}

	/**
	 * Sets the maire
	 *
	 * @param string $population
	 * @return void
	 */
	public function setPopulation($population) {
		$this->population = $population;
	}
    
	/**
	 * Returns the adresseMairie
	 *
	 * @return string $adresseMairie
	 */
	public function getAdresseMairie() {
		return $this->adresseMairie;
	}

	/**
	 * Sets the maire
	 *
	 * @param string $adresseMairie
	 * @return void
	 */
	public function setAdresseMairie($adresseMairie) {
		$this->adresseMairie = $adresseMairie;
	}
    
	/**
	 * Returns the telephone
	 *
	 * @return string $telephone
	 */
	public function getTelephone() {
		return $this->telephone;
	}

	/**
	 * Sets the telephone
	 *
	 * @param string $telephone
	 * @return void
	 */
	public function setTelephone($telephone) {
		$this->telephone = $telephone;
	}
    
	/**
	 * Returns the fax
	 *
	 * @return string $fax
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * Sets the telephone
	 *
	 * @param string $fax
	 * @return void
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}
    
	/**
	 * Returns the courriel
	 *
	 * @return string $courriel
	 */
	public function getCourriel() {
		return $this->courriel;
	}

	/**
	 * Sets the courriel
	 *
	 * @param string $courriel
	 * @return void
	 */
	public function setCourriel($courriel) {
		$this->courriel = $courriel;
	}
    
	/**
	 * Returns the siteInternet
	 *
	 * @return string $siteInternet
	 */
	public function getSiteInternet() {
		return $this->siteInternet;
	}

	/**
	 * Sets the courriel
	 *
	 * @param string $siteInternet
	 * @return void
	 */
	public function setSiteInternet($siteInternet) {
		$this->siteInternet = $siteInternet;
	}
    
	/**
	 * Returns the facebook
	 *
	 * @return string $facebook
	 */
	public function getFacebook() {
		return $this->facebook;
	}

	/**
	 * Sets the courriel
	 *
	 * @param string $facebook
	 * @return void
	 */
	public function setFacebook($facebook) {
		$this->facebook = $facebook;
	}
    
	/**
	 * Returns the autreLien
	 *
	 * @return string $autreLien
	 */
	public function getAutreLien() {
		return $this->autreLien;
	}

	/**
	 * Sets the courriel
	 *
	 * @param string $autreLien
	 * @return void
	 */
	public function setAutreLien($autreLien) {
		$this->autreLien = $autreLien;
	} 
}
?>