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
 * Fait par Adrien
 *
 */
 
class SuivantPrecedantViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{
    
    /**
     * confirme la présence d'un lien pour palier aux difficulté de fluid
     * 
     * @param uid $uidFicheActuelle
     * 
     * @param int $pid
     * 
     * @param string $link
     * @param string $classNext
     * @param string $classPrev
     * @param int $returnUid
     */

    public function render($uidFicheActuelle, $pid, $link, $classNext = 'btn pleft btn-primary btn-rp-lw', $classPrev = 'btn pright btn-primary btn-rp-lw', $returnUid = 0){
        $this->classNext = $classNext;
        $this->classPrev = $classPrev;

        //recup tableau d'uid de la recherche, entré dans un cache depuis fiche Controller
            $sesId = $GLOBALS['TSFE']->fe_user->id;
            //$sesID = "e8ff2ab06c6c0af20dd3d63472d50db7";
            $fiches = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('txeannuaires_search_cache')->get($sesId);
                
        //recup uid actuelle
            $uidFiche = $uidFicheActuelle;
            $pidFiche = $pid;

        //recup type actuel
            $actual = explode('/', $this->getActuallink());
            //url sous la forme http://mondomaine.fr/annuaires/type
            $type = $actual[4];
            //au cas où que l'url ai changée, je sauve l'accès aux élus
            if($type =="les-annuaires" || $type == "fiche") { $type ="les-elus"; }

            //echo '<style>.typo3-debug{position:relative;z-index:1000000;}</style>';
            //\TYPO3\CMS\Core\Utility\DebugUtility::debug($actual, 'actuel');


        //dans tableau de résult pris dans le cache, prendre l'uid suivante, ou précédante
        if (!empty($fiches)){
            $backFiche = '';
            $nextFiche = '';
            $back = '';

            foreach ($fiches as $entree){           
                if($back != '' AND $entree == $uidFiche ){
                    $backFiche = $back;
                }
                if($back != '' AND $back == $uidFiche){
                    $nextFiche = $entree;
                    break;
                }
                $back = $entree;
            }

        //renvoyer une url avec ce changement
            if($link =='back'){
                if($backFiche != ''){
                    $content = $returnUid > 0 ? $backFiche : $this->createURL($backFiche,$type,$link) ;

                    return $content;
                }else {
                    return '';
                }

            }else {
                if($nextFiche != ''){
                    $content = $returnUid > 0 ? $nextFiche : $this->createURL($nextFiche,$type,$link) ;
                    return $content;
                }else {
                    return '';
                }
            }
        }

    }

    public function createURL($uid,$type,$link){
        //l'url est bonne mais realurl créé des problèmes
        /*
        $urlParameters=array(
            'tx_eannuaires_pi1[fiche]'=> $link
        );  
        $url = $this->cObj->getTypoLink_URL('7734', $urlParameters);
        return $url ;
        */
        // $url = $GLOBALS['TSFE']->baseUrl ."les-annuaires/".$type."/?tx_eannuaires_pi1[fiche]=".$uid;
        $url = $this->getActuallink(0, '&tx_eannuaires_pi1[fiche]='.$uid);

        if($link == 'back'){
            $lien = '<a href="'.$url.'" class="'.$this->classPrev.'"> <span>&lt;&lt;</span> Précédent</a>';

        }else {
            $lien = '<a href="'.$url.'" class="'.$this->classNext.'"> Suivant <span>&gt;&gt;</span></a>';
        }

        return $lien;    
        
    }

    
    public function getActuallink($addQueryString = 1, $additionalParams = ''){
        $confLink = array();
        $confLink['parameter'] = $GLOBALS['TSFE']->id;
        $confLink['returnLast'] = 'url';
        $confLink['addQueryString'] = $addQueryString;
        $confLink['additionalParams'] = ($additionalParams != '') ? $additionalParams : '';
        
        $cObjRenderer = new \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer();
        return $cObjRenderer->typoLink_URL($confLink);
        
    }
}