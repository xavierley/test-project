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
 
class PhpFuncViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * Execute la fonction php passé en paramètre
     * 
     * @param string $func
     * 
     * @param array $args
     * 
     * @param string $class
     * 
     * @return string abecedaire
     */
    public function render($func, $args = array(), $class = ''){
        if($class == ''){
            return call_user_func_array($func, $args);
        } else{
            return call_user_func_array(array($class,$func), $args);
        } 
    }
    
    
}