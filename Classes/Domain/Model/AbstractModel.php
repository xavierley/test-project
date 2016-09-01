<?php
namespace Emagineurs\EAnnuaires\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 
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

 use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
 
/**
 *
 *
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AbstractModel extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Sets the file from $_FILES
	 *
	 * @param array $files
	 * @param mixed $object
	 * @return void
	 */
	public function setFileinstancier($files, &$object, $field) {
		if(is_array($files) && count($files) > 0){
			foreach($files as $key => $currentFile){
				if(!empty($currentFile['name'])){
					// On copie le fichier sur le serveur et on crée l'élément correspondant dans FAL
					$file = \Emagineurs\EAnnuaires\Utility\FileUtility::generateFile($currentFile);
					$fileRef = $object->objectManager->get(\Emagineurs\EAnnuaires\Domain\Model\Filereference::class);
					
					// On va commencer à setter les Filereference pour chaque fichier (qu'on attachera par la suite à $object->$field)
					// D'abord les champs "autres" (ex: titre, description, etc ...)
					foreach($currentFile['additional'] as $fileProperty => $propertyValue){
						if($fileProperty != 'identifier'){
							$setter = 'set'.ucFirst($fileProperty);
							$fileRef->$setter($propertyValue);
						}
					}
					
					// On gère ici l'original resource
					$originalResource = \Emagineurs\EAnnuaires\Utility\FileUtility::createFileReference($file);
					$fileRef->setOriginalResource($originalResource);
					
					$fileRef->setTableLocal('sys_file');
					$fileRef->setShowinpreview(TRUE);
						
					$object->$field->attach($fileRef);
				}
			}
		}
	}
	
}
?>