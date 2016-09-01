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
class Filereference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference {

    /**
     * @var string
     */
    protected $tableLocal;

    /**
     * @var string
     */
    protected $fieldname;
	
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $alternative;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $downloadname;

    /**
     * @var bool
     */
    protected $autoplay;

    /**
     * @var bool
     */
    protected $showinpreview;

    /**
     * Get tableLocal
     *
     * @return string
     */
    public function getTableLocal(){
        return $this->tableLocal;
    }

    /**
     * Set tableLocal
     *
     * @param string $tableLocal
     * @return void
     */
    public function setTableLocal($tableLocal){
        $this->tableLocal = $tableLocal;
    }

    /**
     * Get fieldname
     *
     * @return string
     */
    public function getFieldname(){
        return $this->fieldname;
    }

    /**
     * Set fieldname
     *
     * @param string $fieldname
     * @return void
     */
    public function setFieldname($fieldname){
        $this->fieldname = $fieldname;
    }

    /**
     * Get alternative
     *
     * @return string
     */
    public function getAlternative(){
        return $this->alternative !== null ? $this->alternative : $this->getOriginalResource()->getAlternative();
    }

    /**
     * Set alternative
     *
     * @param string $alternative
     * @return void
     */
    public function setAlternative($alternative){
        $this->alternative = $alternative !== null ? $alternative : $this->getOriginalResource()->getAlternative();
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(){
        return $this->description !== null ? $this->description : $this->getOriginalResource()->getDescription();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description){
        $this->description = $description !== null ? $description : $this->getOriginalResource()->getDescription();
    }

    /**
     * Get link
     *
     * @return mixed
     * @return void
     */
    public function getLink(){
        return $this->link !== null ? $this->link : $this->getOriginalResource()->getLink();
    }

    /**
     * Set link
     *
     * @param string $link
     * @return void
     */
    public function setLink($link){
        $this->link = $link !== null ? $link : $this->getOriginalResource()->getLink();
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(){
        return $this->title !== null ? $this->title : $this->getOriginalResource()->getTitle();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title){
        $this->title = $title !== null ? $title : $this->getOriginalResource()->getTitle();
    }

    /**
     * Get downloadname
     *
     * @return string
     */
    public function getDownloadname(){
        return $this->downloadname;
    }
	
    /**
     * Set downloadname
     *
     * @param string $downloadname
     * @return void
     */
    public function setDownloadname($downloadname){
        $this->downloadname = $downloadname;
    }

    /**
     * Get showinpreview
     *
     * @return bool
     */
    public function getShowinpreview(){
        return $this->showinpreview;
    }

    /**
     * Set showinpreview
     *
     * @param bool $showinpreview
     * @return void
     */
    public function setShowinpreview($showinpreview){
        $this->showinpreview = $showinpreview;
    }

    /**
     * Set autoplay
     *
     * @param bool $autoplay
     * @return void
     */
    public function setAutoplay($autoplay){
        $this->autoplay = $autoplay;
    }

    /**
     * Get autoplay
     *
     * @return bool
     */
    public function getAutoplay(){
        return $this->autoplay;
    }

}
?>