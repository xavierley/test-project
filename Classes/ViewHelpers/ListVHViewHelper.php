<?php
namespace Emagineurs\EAnnuaires\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Xavier LEY <xley@e-magineurs.com>, E-magineurs
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
 * @package e_magineurs
 * @subpackage ViewHelpers
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class ListVHViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper{

    /**
     * @param int $file The file pour lequel renvoyer le fichier
     * @validate $file String
     * @return string poid du fichier
     */
    public function render($displayDoc='') {
		$vhPath = 'typo3conf/ext/e_lib/Classes/ViewHelpers';

		$content = '';
	
		$listeDossiers = GeneralUtility::get_dirs($vhPath);
		$listeFichiers = GeneralUtility::getFilesInDir($vhPath);
		
		if(in_array('docs',$listeDossiers)){
			$listeFichierDocs = GeneralUtility::getFilesInDir($vhPath.'/docs');
		}
	
		$content .= 
			'<style>
				.docVH{display:none;}
				.ListVHStyle pre {
					white-space: -moz-pre-wrap; /* Mozilla, supported since 1999 */
					white-space: -pre-wrap; /* Opera */
					white-space: -o-pre-wrap; /* Opera */
					white-space: pre-wrap; /* CSS3 - Text module (Candidate Recommendation) http://www.w3.org/TR/css3-text/#white-space */
					word-wrap: break-word; /* IE 5.5+ */
					
					font-size:12px;
					margin:10px 0 5px;
				}
			</style>
			<script>
				jQuery(document).ready(function() {
					displayDoc(doc);
				});
				
				function displayDoc(doc){		
					jQuery("#"+doc).toggle();
				}
			</script>';
		
		$content .= '<div class="ListVHStyle"><ul>';
		
		foreach($listeFichiers as $viewHelper){
			$nomVH = substr($viewHelper,0,-4);
			$lien = '';
			
			if(is_array($listeFichierDocs) && !empty($listeFichierDocs) && count($listeFichierDocs)){
				foreach($listeFichierDocs as $docs){
					$nomDoc = substr($docs,0,-4);
				
					if($nomVH == $nomDoc){
						$lienDoc = '<a href="javascript:void(0)" onclick="displayDoc(\''.$nomDoc.'\');">Afficher/cacher la doc de ce viewHelper</a> ';
					
						$contenuDoc = '<pre>'.htmlspecialchars(file_get_contents($vhPath.'/docs/'.$docs)).'</pre>';
					
						$lien = ' - '.$lienDoc;
					}
				}
			}
		
			$content .= '<li>'.$nomVH.$lien.'<br/>';
			$content .= '<div class="docVH" id="'.$nomVH.'">'.$contenuDoc.'</div>';
			$content .= '<hr/></li>';
		}
	
		$content .= '</ul></div>';
	
		return $content;
	
    }
	
}

?>