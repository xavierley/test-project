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
class Search extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
    
	/**
	 * __construct
	 *
	 * @return Fiche
	 */
	public function __construct(){
        $settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_eannuaires.']['settings.'];  
        
        $propertiesArray = $settings['search.']['properties.'];
        
    	foreach ($propertiesArray as $value){
    		$this->$value = '';
    	}
    }

	/**
	 * __call
	 *
	 * @return Fiche
	 */
	public function __call($method,$arguments){
        $property = strtolower(substr($method,3));
        
        if(strtolower(substr($method,0,3)) == 'get'){
            $result = $this->__get($property);
            return $result;
        }
        if(strtolower(substr($method,0,3)) == 'set'){
            $this->__set($property,$arguments[0]);
        }
    }    
    
	/**
	 * Sets the propery named $key
	 *
	 * @param \mixed $value
	 * @return void
	 */
    public function __set($key, $value){
    	$this->$key = $value;
    }

	/**
	 * Returns the $value
	 *
	 * @return \mixed $value
	 */
    public function __get($value){
    	return $this->$value;
    }

}
?>