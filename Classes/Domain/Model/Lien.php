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
class Lien extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * titre
	 *
	 * @var \string
	 */
	protected $titre;

	/**
	 * url
	 *
	 * @var \string
	 */
	protected $url;

	/**
	 * isFacebook
	 *
	 * @var \boolean
	 */
	protected $isFacebook;

	/**
	 * isTwitter
	 *
	 * @var \boolean
	 */
	protected $isTwitter;

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
	 * Returns the url
	 *
	 * @return \string $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets the url
	 *
	 * @param \string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Returns the isFacebook
	 *
	 * @return \boolean $isFacebook
	 */
	public function getIsFacebook() {
		return $this->isFacebook;
	}

	/**
	 * Sets the isFacebook
	 *
	 * @param \boolean $isFacebook
	 * @return void
	 */
	public function setIsFacebook($isFacebook) {
		$this->isFacebook = $isFacebook;
	}

	/**
	 * Returns the isTwitter
	 *
	 * @return \boolean $isTwitter
	 */
	public function getIsTwitter() {
		return $this->isTwitter;
	}

	/**
	 * Sets the isTwitter
	 *
	 * @param \boolean $isTwitter
	 * @return void
	 */
	public function setIsTwitter($isTwitter) {
		$this->isTwitter = $isTwitter;
	}

}
?>