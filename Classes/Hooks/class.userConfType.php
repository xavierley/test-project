<?php

Class userConfType extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper { 

    public function makeTypeArray($conf){
        // On récupère la liste des types définie dans l'extension manager.
        $confExtTplAnnuaires = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['e_annuaires']);
        $types = explode(',',$confExtTplAnnuaires['types']);
        
        // On récupère les valeurs enregistrés pour la conf actuelle
        $confType = $confExtTplAnnuaires['confType'];
                
        // On récupère la liste des champs de la table des fiches annuaires
        $fieldListArray = array(); 
        $fieldListArrayBegin =  $GLOBALS['TYPO3_DB']->admin_get_fields('tx_eannuaires_domain_model_fiche'); 
        
        // On exclut les tables dont on sait qu'elle ne seront pas utilsé dans le TCA
        $excludeFields = array(
            'uid',
            'pid',
            'cruser_id',
            'deleted',
            't3ver_oid',
            't3ver_id',
            't3ver_wsid',
            't3ver_label',
            't3ver_state',
            't3ver_stage',
            't3ver_count',
            't3ver_tstamp',
            't3ver_move_id',
            't3_origuid',
            'l10n_parent',
            'l10n_diffsource'
        );
        
        foreach($fieldListArrayBegin as $field => $value){
            if(!in_array($field,$excludeFields)){
                $fieldListArray[] = $field;
            }
        }
		
		sort($fieldListArray);
		        
        // On commence à générer le html
        $content = '<table border="1" >';
        
        // On créé un tableau avec dans chaque ligne, une type de fiche
		if(!empty($confType)){
			foreach($types as $type){
				$infoType = explode('-',$type);
				
				$content .= '<tr>';
					$content .= '<td>';
						$content .= $infoType[1].'<br /><br /> - afficher : <br /> - Ordre : <br /> - locallang:<br /> - nouvel onglet:';
					$content .= '</td>';
					$content .= '<td>';
						$content .= '<table>';
							$content .= '<tr>';
							
							// Pour chaque champs on ajoute une case à cocher et un champ texte pour determiner si un champ doit être afficher et dans quel ordre
							// On remplit par défaut les champs si on a des valeurs enregistrés.
							foreach($fieldListArray as $key => $field){
								$content .= '<td style="width:5px; height:5px;">&nbsp;&nbsp;&nbsp;</td>';
								$content .= '<td>';
									$content .= '<label for="'.$field.'_'.$infoType[0].'">'.$field.'</label>';
									
									if(($confType[$infoType[0]][$field]['value'] == $field) || ($field == 'title')){
										$content .= '<input type="checkbox" checked="checked" id="'.$field.'_'.$infoType[0].'" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][value]" value="'.$field.'" />';
									} else {   
										$content .= '<input type="checkbox" id="'.$field.'_'.$infoType[0].'" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][value]" value="'.$field.'" />';
									}
									
									$content .= '<br />';
									if(intval($confType[$infoType[0]][$field]['sorting'])){
										$content .= '<input value="'.intval($confType[$infoType[0]][$field]['sorting']).'" size="1" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][sorting]" />';
									} else {  
										$content .= '<input value="" size="1" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][sorting]" />';
									}
									
									$content .= '<br />';
									if($confType[$infoType[0]][$field]['label']){
										$content .= '<input value="'.$confType[$infoType[0]][$field]['label'].'" size="10" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][label]" />';
									} else {  
										$content .= '<input value="" size="10" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][label]" />';
									}
									
									$content .= '<br />';
									if($confType[$infoType[0]][$field]['newTab']){
										$content .= '<input value="'.$confType[$infoType[0]][$field]['newTab'].'" size="10" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][newTab]" />';
									} else {  
										$content .= '<input value="" size="10" style="width:auto;" type="text" name="'.$conf['fieldName'].'['.$infoType[0].']['.$field.'][newTab]" />';
									}
									
								$content .= '</td>';
								$content .= '<td style="width:5px; height:5px;">&nbsp;&nbsp;&nbsp;</td>';
							}
							$content .= '</tr>';
						$content .= '</table>';
					$content .= '</td>';
				$content .= '</tr>';
				
			}
		}
        
        $content .= '</table>';
        
        // On retourne le html généré
        if($types){
            return $content;
        }
    }

}





?>