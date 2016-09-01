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
 
class FindBinomeCantonViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * categorieRepository
     *
     * @var \Emagineurs\EGmap\Domain\Repository\CategorieRepository
     * @inject
     */
    protected $categorieRepository = NULL;
    

    /**
     * Retrouve le ts config passé en paramètre
     * 
     * @param int $canton
     * 
     * @param int $type
     * 
     * @param int $uidFicheActuelle
     *
     * @param int $pid
     * 
     * @param int $categories
     *
     * @return string $urlBinome
     *
     */


    public function render($canton, $type, $uidFicheActuelle, $pid, $count=''){       
        $table = 'tx_eannuaires_domain_model_mandat AS M';
        $select = 'M.uid, M.canton';
        $where = "M.canton = ".$canton." AND M.typemandat = ".$type." AND M.deleted =0 AND M.hidden = 0";
        $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
        
		if(is_array($req) && count($req) > 0){
			foreach($req as $mandat) {
				$table2 = 'tx_eannuaires_domain_model_fiche AS F, tx_eannuaires_domain_model_mandat AS M';
				$select2 = 'F.uid';
				// $where2 = "F.deleted=0 AND F.hidden = 0 AND F.mandats LIKE ('%".$mandat['uid']."%') AND F.uid !='".$uidFicheActuelle."'; ";
				$where2 = "F.deleted=0 AND F.hidden = 0 AND F.mandats = ".$mandat['uid']." AND F.uid != ".$uidFicheActuelle;
				$req2 = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select2,$table2,$where2,'F.uid');
				$ficheUid[] = $req2[0]['uid'];
			}
			
			rsort($ficheUid);
			$ficheUid = $ficheUid[0];
		}


        $urlBinome = array(
            'pid' => $pid,
            'fiche' => $ficheUid
        );
        if ($count!='' AND $ficheUid !='' ){
            //pas besoin de compter les valeurs, le but et de savoir si il y a un autre élus avec ce canton ou non
            return "ok";
        }

        return $urlBinome;
        
    }
}