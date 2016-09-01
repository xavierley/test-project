<?php
namespace Emagineurs\EAnnuaires\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Xavier Ley <xley@e-magineurs.com>
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
 * @package e_annuaires
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FileUtility {
	
	/*
	 * On génère ici l'entrée dans FAL et on crée le fichier sur le serveur => A passer dans utility
	 */
	public static function generateFile($fileInfo){
		$fileMedia = $fileInfo['name'];
		$fileTmpMedia = $fileInfo['tmp_name'];

		// On crée l'entrée dans FAL pour l'image/le fichier  uploadé
		$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\StorageRepository::class);
		$storage = $storageRepository->findByUid(1);

		// On va placé l'image dans le repertoire fileadmin/Edition-Annuaire-Front, que l'on va créer s'il n'existe pas
		$identifier = 'edition-annuaire-front';
		$absoluteIdentiferPath = $storage->getConfiguration()['basePath'].$identifier;
		
		if(file_exists($absoluteIdentiferPath)) {
			$folder = $storage->getFolder($identifier);
		} else {
			$folder = $storage->createFolder($identifier);
		}
		
		// On ajoute l'image/le fichier
		$file = $storage->addFile($fileTmpMedia, $folder, $fileMedia);
		
		return $file;
	}
	
	public static function createFileReference($file){
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		$data = array(
			'uid_local' => $file->getUid()
		);
		$fileRef = $resourceFactory->createFileReferenceObject($data);
		return $fileRef;
	}

}