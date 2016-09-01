<?php
namespace Emagineurs\EAnnuaires\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 * (c) 2013 Xavier Ley <xley@e-magineurs.com>
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
 * use Emagineurs\EAnnuaires\Domain\Model\Fiche;
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CategorieRepository extends AbstractRepository {

    public function findChildren($uidCat){
		$query = $this->createQuery();

        // On ne récupère pas les enregistrement de la page courrante
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
        $query->matching(
            $query->equals('parent',$uidCat)
        );   
        
		return $query->execute();
    }
    
    public function findFromSettings($cats = array()){
		$query = $this->createQuery();

        // On ne récupère pas les enregistrement de la page courrante
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
        $constraint = array();
        
        if(is_array($cats) && count($cats) > 0 && !empty($cats)){
            foreach($cats as $cat){
                $constraint[] = $query->equals('uid',$cat);
            }
        }
        
        $query->matching(
            $query->logicalOr($constraint)
        );  
        
		return $query->execute();
    }
    
    public function findCatFirstLvl(){
		$query = $this->createQuery();

        // On ne récupère pas les enregistrement de la page courrante
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
        
        $query->matching(
            $query->equals('parent',0)
        );   
        
		return $query->execute();    
    }
	
}
?>