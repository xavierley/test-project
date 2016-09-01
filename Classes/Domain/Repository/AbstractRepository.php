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
class AbstractRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
  
    /**
     * Copied from the sources of TYPO3 and modified in order to exclude the storage page from query settings
     *
     * Dispatches magic methods (findBy[Property]())
     *
     * @param string $methodName The name of the magic method
     * @param string $arguments The arguments of the magic method
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException
     * @return mixed
     * @api
     */
    public function __call($methodName, $arguments)
    {
        if (substr($methodName, 0, 6) === 'findBy' && strlen($methodName) > 7) {
            $propertyName = lcfirst(substr($methodName, 6));
            $query = $this->createQuery();

			/***** Added by Xavier Ley beginning *****/
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			/***** Added by Xavier Ley end *****/

            $result = $query->matching($query->equals($propertyName, $arguments[0]))->execute();
            return $result;
        } elseif (substr($methodName, 0, 9) === 'findOneBy' && strlen($methodName) > 10) {
            $propertyName = lcfirst(substr($methodName, 9));
            $query = $this->createQuery();

			/***** Added by Xavier Ley beginning *****/
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			/***** Added by Xavier Ley end *****/

            $result = $query->matching($query->equals($propertyName, $arguments[0]))->setLimit(1)->execute();
            if ($result instanceof QueryResultInterface) {
                return $result->getFirst();
            } elseif (is_array($result)) {
                return isset($result[0]) ? $result[0] : null;
            }
        } elseif (substr($methodName, 0, 7) === 'countBy' && strlen($methodName) > 8) {
            $propertyName = lcfirst(substr($methodName, 7));
            $query = $this->createQuery();

			/***** Added by Xavier Ley beginning *****/
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			/***** Added by Xavier Ley end *****/

            $result = $query->matching($query->equals($propertyName, $arguments[0]))->execute()->count();
            return $result;
        }
        throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException('The method "' . $methodName . '" is not supported by the repository.', 1233180480);
    }
	
}
?>