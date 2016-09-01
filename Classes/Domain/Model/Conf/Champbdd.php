<?php
namespace Emagineurs\EAnnuaires\Domain\Model\Conf;

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
class Champbdd extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var \string
	 */
	protected $title;
	
	/**
	 * sorting
	 *
	 * @var \integer
	 */
	protected $sorting;
	
	/**
	 * label
	 *
	 * @var \string
	 */
	protected $label;
	
	/**
	 * tablabel
	 *
	 * @var \string
	 */
	protected $tablabel;
	
	/**
	 * typeannuaire
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire
	 */
	protected $typeannuaire;

	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the sorting
	 *
	 * @return \integer $sorting
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * Sets the sorting
	 *
	 * @param \integer $sorting
	 * @return void
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * Returns the label
	 *
	 * @return \string $label
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Sets the label
	 *
	 * @param \string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Returns the tablabel
	 *
	 * @return \string $tablabel
	 */
	public function getTablabel() {
		return $this->tablabel;
	}

	/**
	 * Sets the tablabel
	 *
	 * @param \string $tablabel
	 * @return void
	 */
	public function setTablabel($tablabel) {
		$this->tablabel = $tablabel;
	}

	/**
	 * Returns the typeannuaire
	 *
	 * @return \Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire $typeannuaire
	 */
	public function getTypeannuaire() {
		return $this->typeannuaire;
	}

	/**
	 * Sets the typeannuaire
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire $typeannuaire
	 * @return void
	 */
	public function setTypeannuaire(\Emagineurs\EAnnuaires\Domain\Model\Conf\Typeannuaire $typeannuaire) {
		$this->typeannuaire = $typeannuaire;
	}

}
?>