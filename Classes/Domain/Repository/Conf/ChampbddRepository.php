<?php
namespace Emagineurs\EAnnuaires\Domain\Repository\Conf;

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
class ChampbddRepository extends \Emagineurs\EAnnuaires\Domain\Repository\AbstractRepository {

    public function findAll(){
		$query = $this->createQuery();

        // On ne récupère pas que les enregistrement de la page courrante
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
                				
		return $query->execute();
    }
	
    public function selectConfType($idType){
		$query = $this->createQuery();

        // On ne récupère pas que les enregistrement de la page courrante
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		
		$query->matching(
			$query->equals('typeannuaire',$idType)
		);
		
		$query->setOrderings(
			array(
				'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
			)
		);
                
		return $query->execute();
    }
	
	public function publicPersistAll(){
		$this->persistenceManager->persistAll();
	}
	
}
?>