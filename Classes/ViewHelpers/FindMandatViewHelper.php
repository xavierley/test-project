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
 
class FindMandatViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * categorieRepository
     *
     * @var \Emagineurs\EGmap\Domain\Repository\CategorieRepository
     * @inject
     */
    protected $categorieRepository = NULL;
    

    /**
     * Retrouve le mandat d'une commune
     * 
     * @param int $commune
     * 
     * @param int $typemandat
     *
     * @param int $commission
     */

    public function render($commune, $typemandat=0, $commission=0){
	
        //maire
        if($typemandat == 4){
            //récup le mandat de maire de cette commune
            $id_mandat ='';
            $req ='';

            //récup l'uid de la fiche de cette commune
            $table = 'tx_eannuaires_domain_model_mandat AS M ';
            $select = 'M.uid, M.typemandat, M.communemandat';
            $where = 'M.typemandat = '.$typemandat.' AND M.communemandat = ' .$commune .' AND M.deleted = 0 ORDER BY M.uid DESC LIMIT 1' ;
            $id_mandat = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
                    
            if(!empty($id_mandat)){
                
                $id_mandat = $id_mandat[0];

                //Récup tous les élus avec possiblement ce mandat dans leur liste de mandats (like est approximatif ici : mandat : 2 ou mandat : 20)
                //$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
                $table = 'tx_eannuaires_domain_model_fiche AS F';
                $select = 'F.*';
                $where = ' F.deleted = 0 AND F.typeelement = 7 AND F.mandats LIKE (\'%'.$id_mandat['uid'].'%\') AND deleted = 0';
                $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
                //return $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;

                //réduire le côté approximatif
				// commenté par xavier, le deleted = 0 a été ajouté dans la requete au dessus
                // $tab=array();
                // foreach ($req as $r){
                    // $listmandats =array();
                    // $table = 'tx_eannuaires_domain_model_fiche AS F';
                    // $select = 'F.*';
                    // $where = 'F.deleted = 0 AND F.uid ='.$r['uid'];
                    // $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);
					
					// note de xavier, la ligne suivante ne renvoie rien, il faut $req[0]['mandats'] et pas $req['mandats']
                    // $listmandats = explode(',',$req['mandats']);
                    // if(in_array($id_mandat, $listmandats)){
                        // $tab[]=$req;
                    // }
                // }

                // return $tab[0]; 
                return $req[0]; 
            }
        //les autres mandats, vérirfie si la fiche n'est pas supprimée et si il a toujours des mandats
        }else{
            $table = 'tx_eannuaires_domain_model_commission AS C';
            $select = 'C.membres';
            $where = 'C.deleted = 0 AND C.hidden=0 AND C.uid ='.$commission;
            $ficheelu = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$where);

            $tableb = 'tx_eannuaires_domain_model_fiche AS F';
            $selectb = 'F.mandats';
            $whereb = ' F.deleted = 0 AND F.hidden=0 AND F.typeelement = 7 AND F.uid ='.$ficheelu[0]['membres'];
            $req = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($selectb,$tableb,$whereb);
            
            if(!empty($req )){
                return true;
            }

        }
    
    }
}