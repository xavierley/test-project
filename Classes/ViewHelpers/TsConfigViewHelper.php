<?php
namespace Emagineurs\EAnnuaires\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Xavier Ley <xley@e-magineurs.com>, E-magineurs
 *  
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
 * @package e_lib
 * @subpackage ViewHelpers
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
class TsConfigViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * Retrouve le ts config passé en paramètre
     * 
     * @param int $page
     * 
     * @param string $key
     *
     * @param string $tsConfigPath
     * 
     * @return string value
     */


    public function render($page=0, $key, $tsConfigPath){
        $pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($page);    
        $wantedConf = array(); 

        $confArray = explode('.',$tsConfigPath);

        if(is_array($confArray) && !empty($confArray)){
            foreach($confArray as $conf){
                $wantedConf = $pagesTsConfig[$conf.'.'];
                $pagesTsConfig = $wantedConf;
            }
        }
        $value = $wantedConf[$key];
        
        return $value;

    }
    
    
}