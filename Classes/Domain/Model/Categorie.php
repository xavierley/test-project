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
class Categorie extends \TYPO3\CMS\Extbase\Domain\Model\Category {

	/**
	 * title
	 *
	 * @var \string
	 */
	protected $title;

	/**
	 * parent
	 *
	 * @var \Emagineurs\EAnnuaires\Domain\Model\Categorie
	 */
	protected $parent;
    
	/**
	 * image
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $images;
    
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
	 * Returns the parent
	 *
	 * @return \Emagineurs\EAnnuaires\Domain\Model\Categorie $parent
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Sets the parent
	 *
	 * @param \Emagineurs\EAnnuaires\Domain\Model\Categorie $parent
	 * @return void
	 */
	public function setParent(\Emagineurs\EAnnuaires\Domain\Model\Categorie $parent) {
		$this->parent = $parent;
	}
    
	/**
	 * Returns the the children of the current cat
	 *
	 * @return array $children
	 */
	public function getChildren() {
        $catRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Emagineurs\EAnnuaires\Domain\Repository\CategorieRepository');
                
        $children = $catRepository->findChildren($this->getUid());
                
        return $children;
	}

	/**
	 * Returns the images
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $images
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Sets the images
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $images
	 * @return void
	 */
	public function setImages(\TYPO3\CMS\Extbase\Domain\Model\FileReference $images) {
		$this->images = $images;
	}

}
?>