<?php
namespace Emagineurs\EAnnuaires\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ian SEBBAGH <isebbagh@e-magineurs.com>, E-magineurs
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

 /***
 
{namespace egmap=Emagineurs\ELib\ViewHelpers} 
 
<egmap:sort object="{cats}" as="catsTri" sortBy="title" order="ASC" >
</egmap:sort>


{egmap:sort(object: cats, as: 'catsTri', sortBy: 'title', order: 'ASC')}
 
 */
 
 
/**
 *
 *
 * @package e_gmap
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
class SortViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {


	/**
	 * Sort array.
	 *
	 * @param array $object The array or \TYPO3\CMS\Extbase\Persistence\ObjectStorage to iterated over
	 * @param string $as The name of the iteration variable
	 * @param string $sortBy The field to use for sorting
	 * @param string $order The order of sorting ASC or DESC
	 * @return string Rendered string
	 */
	public function render($object, $sortBy, $order = 'ASC', $as=null) {
		$objectSorted = $this->sortObject($object, $sortBy, $order);
		$output = null;
		if(!empty($as)){
			$this->templateVariableContainer->add($as, $objectSorted);
			$output = $this->renderChildren();
			$this->templateVariableContainer->remove($as);
		}
		if(empty($output)) 
			$output = $objectSorted;
		return $output;
	}
	
	public function sortObject($object, $sortBy, $order){
		if($object instanceof QueryResultInterface){
			$object = $object->toArray();
		}
		
		$objectSorted = array();
		
		foreach($object as $key => $value){
			$index = $this->getValue($value, $sortBy);
			while (isset($objectSorted[$index])) {
				$index .= '.1';
			}
			$objectSorted[$index] = $value;
			if($order == 'DESC'){
				krsort($objectSorted);
			}else{
				ksort($objectSorted);
			}
		}	
		
		return $objectSorted;
	}
	
	public function getValue($object, $field){
		$value =  \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getPropertyPath($object, $field);
		return $value;
	}
}

?>