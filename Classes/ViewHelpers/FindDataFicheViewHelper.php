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
 
class FindDataFicheViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * categorieRepository
     *
     * @var \Emagineurs\EGmap\Domain\Repository\CategorieRepository
     * @inject
     */
    protected $categorieRepository = NULL;
    

    /**
     * Retrouve les infos d'une news à partir de son poi uid
     * 
     * @param string $data
     * 
     */

    public function render($uidfiche){
        $select='title';
        $table = 'tx_eannuaires_domain_model_fiche AS F ';
        $where = 'F.uid = '.$uidfiche.' LIMIT 1' ;
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
        $data =  $req[0][$select];
            

        return $data; 
        
    
    }
}