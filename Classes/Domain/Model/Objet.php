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
class Objet extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * titre
	 *
	 * @var \string
	 */
	protected $titre;

	/**
	 * texte
	 *
	 * @var \string
	 */
	protected $texte;

	/**
	 * Returns the titre
	 *
	 * @return \string $titre
	 */
	public function getTitre() {
		return $this->titre;
	}

	/**
	 * Sets the texte
	 *
	 * @param \string $titre
	 * @return void
	 */
	public function setTitre($titre) {
		$this->titre = $titre;
	}

	/**
	 * Returns the texte
	 *
	 * @return \string $texte
	 */
	public function getTexte() {
		return $this->texte;
	}

	/**
	 * Sets the texte
	 *
	 * @param \string $texte
	 * @return void
	 */
	public function setTexte($texte) {
		$this->texte = $texte;
	}

}
?>