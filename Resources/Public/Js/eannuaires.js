//imageLoaderPanier = '<img class="imageLoaderPanier" src="typo3conf/ext/e_gestion_panier/Resources/Public/Icons/loader.gif" style="height:40px; width:40px;" />';
jQuery.noConflict();

imageLoaderPanier = '<img class="imageLoaderPanier" src="../typo3conf/ext/e_annuaires/Resources/Public/Icons/loader.gif" style="margin-top:10px;margin-left:5px;width:24px;height:24px;background-color:#000;border-radius:5px;padding:10px;" />';


jQuery(document).ready(function(){	
	// Js pour le module de conf du tca
	if(jQuery('.ConfTca').length > 0){
		moduleAnnuaireEvents();	
		
		// On genere l'affichage de la conf des champs pour chaque type
		generateDetailConf();
		
		jQuery('#generateConf').click(function(){		
			jQuery(this).after(imageLoaderPanier); 
			generateConf();
		});
	} else if(jQuery('.ConfForm').length > 0){
		// Js pour le module de conf des formulaire d'edition frontend
		moduleFormEvents();
	}	
});

function moduleFormEvents(){	
	// Génération de la liste des champs a utilisé dans le formulaire
	jQuery('.typeButton').click(function(e){
		idItem = jQuery(this).val();
		jQuery('.currentTypeConf').html(imageLoaderPanier);
		jQuery('.currentTypeConf').attr('id','confTypeNum-'+idItem);
		generateDetailConfForm(jQuery('#confTypeNum-'+idItem));
		e.stopImmediatePropagation();
    });   	
}

function eventsAfterFormGeneration(){
	jQuery('.showField').click(function(e){
		jQuery(this).parents('.firstLineConf').addClass('disabledField').find('.fieldDisabled').show();
		jQuery(this).next('.hideField').show();
		e.stopImmediatePropagation();
	});   
	jQuery('.hideField').click(function(e){
		jQuery(this).parents('.firstLineConf').removeClass('disabledField').find('.fieldDisabled').hide();
		jQuery(this).hide();
		e.stopImmediatePropagation();
	});   
	
	// Gestion du sorting des champs
	jQuery('.fieldConf').each(function(index){
		jQuery(this).attr('data-sorting',index);
	});
	jQuery('.movedown').click(function(e){
		if(jQuery(this).parents('.fieldConf').next('.fieldConf').length > 0){
			movedDownItem = jQuery(this).parents('.fieldConf').html();
			movedDownSorting = jQuery(this).parents('.fieldConf').data('sorting');
			
			movedUpItem = jQuery(this).parents('.fieldConf').next('.fieldConf').html();
			movedUpSorting = jQuery(this).parents('.fieldConf').next('.fieldConf').data('sorting');
			
			jQuery('#field-'+movedDownSorting).html(movedUpItem);
			jQuery('#field-'+movedUpSorting).html(movedDownItem);
			
			eventsAfterFormGeneration();
		}
		e.stopImmediatePropagation();
	});
	jQuery('.moveup').click(function(e){
		if(jQuery(this).parents('.fieldConf').prev('.fieldConf').length > 0){
			movedUpItem = jQuery(this).parents('.fieldConf').html();
			movedUpSorting = jQuery(this).parents('.fieldConf').data('sorting');
			
			movedDownItem = jQuery(this).parents('.fieldConf').prev('.fieldConf').html();
			movedDownSorting = jQuery(this).parents('.fieldConf').prev('.fieldConf').data('sorting');
			
			jQuery('#field-'+movedDownSorting).html(movedUpItem);
			jQuery('#field-'+movedUpSorting).html(movedDownItem);
			
			eventsAfterFormGeneration();
		}
		e.stopImmediatePropagation();
	});
	
	jQuery('.generateTemplateButton').click(function(e){
		jQuery('.generateTemplateButton').after(imageLoaderPanier);
		generateFormTemplate();
		e.stopImmediatePropagation();
	});
}
	
function moduleAnnuaireEvents(){		
	// Sauvegarde du chemin vers le locallang
	jQuery('#saveLocallang').click(function(e){
		locallangPath = jQuery('#locallangPath').val();		
		jQuery(this).after(imageLoaderPanier); 
		saveLocallang(locallangPath);
		e.stopImmediatePropagation();
    });   
	
	// Sauvegarde du nouveau type d'annuaire
	jQuery('#saveNewType').click(function(e){
		newTypeName = jQuery('#newTypeName').val();		
		jQuery(this).after(imageLoaderPanier); 
		saveNewType(newTypeName);
		e.stopImmediatePropagation();
    });   
	
	// Suppression du type d'annuaire
	jQuery('.deleteTypeButton').click(function(e){
		typeToDelete = jQuery(this).attr('id');		
		jQuery(this).after(imageLoaderPanier); 
		deleteType(typeToDelete);
		e.stopImmediatePropagation();
    });   
	
	// Affiche/cache la conf du type d'annuaire
	jQuery('.showHideConf').change(function(e){
		jQuery('.confTypeNum'+jQuery(this).val()).toggle();
		e.stopImmediatePropagation();
    });  
	
	// Sauvegarde du contenu du champ
	jQuery('.saveConf').click(function(e){
		jQuery(this).after(imageLoaderPanier); 
		idTypeArray = jQuery(this).attr('id').split('-');
		saveConf(idTypeArray[1]);
		e.stopImmediatePropagation();
    }); 
	
	// Ajout du champ choisi dans le menu déroulant
	jQuery('.ListeChampBdd > select').click(function(e){	
		jQuery(this).attr("disabled", true);
		
		newFieldName = jQuery(this).val();
		item = jQuery(this);
		
		if(newFieldName != 0){
			newFieldTypeInfo = item.attr('id');
			newFieldTypeArray = newFieldTypeInfo.split('-');
			newFieldType = newFieldTypeArray[1];
			
			jQuery('#confTypeNum-'+newFieldType).append(imageLoaderPanier); 
						
			countSorting = (jQuery('#confTypeNum-'+newFieldType+' .fieldConf').length)+1;
		
			addNewField(newFieldName,newFieldType,countSorting);
		}
				
		e.stopImmediatePropagation();
    });   
	
	// Affichage de la conf du TCA au survol
	jQuery('.ListeChampBdd > select > option.displayConf').click(function(e){
		jQuery(this).parents('select').next('.infoChamp').html(imageLoaderPanier); 
		fieldName = jQuery(this).prev().val();
		idSelect = jQuery(this).parents('select').attr('id');
		getConfField(fieldName,idSelect);
		e.stopImmediatePropagation();
	});   
}

function eventsAfterConfGeneration(){
	// On deplace le champ le champ vers le bas
	jQuery('.increaseSorting').click(function(e){
		arrayIdField = jQuery(this).attr('id').split('-');
		idField = arrayIdField[1];		
		
		increaseSorting(idField,jQuery(this));
		
		e.stopImmediatePropagation();
    });
	
	// On deplace le champ le champ vers le haut
	jQuery('.decreaseSorting').click(function(e){
		arrayIdField = jQuery(this).attr('id').split('-');
		idField = arrayIdField[1];		
		
		decreaseSorting(idField,jQuery(this));
		e.stopImmediatePropagation();
    });
	
	// On deplace le champ le champ vers le haut
	jQuery('.deleteChamp').click(function(e){
		arrayIdField = jQuery(this).attr('id').split('-');
		idField = arrayIdField[1];		
		
		jQuery(this).parents('.firstLineConf').append(imageLoaderPanier); 
		
		deleteChamp(idField);
		e.stopImmediatePropagation();
    });
}

function saveLocallang(locallangPath){
    jQuery.ajax({
       url: 'index.php?ajaxID=EAnnuaires',
        type: 'POST',
        dataType : "json",
        data: {
            request:{locallangPath:locallangPath},
			type:'saveLocallang'
        },
        success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#saveLocallang').after('<p>'+data.message+'</p>');
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			jQuery('.imageLoaderPanier').remove();
			jQuery('#saveLocallang').after('<p>Une erreur s\'est produite lors de l\'exécution du script</p>');
        }
    });  
}

function saveNewType(newTypeName){
    jQuery.ajax({
       url: 'index.php?ajaxID=EAnnuaires',
        type: 'POST',
        dataType : "json",
        data: {
            request:{newTypeName:newTypeName},
			type:'saveNewType'
        },
        success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('.listeTypes ul').append(data.contentType);
			jQuery('.listConf').append(data.contentConf);
			
			moduleAnnuaireEvents();	
			generateDetailConf('#confTypeNum-'+data.idType);
			
			if(data.message != 0){
				jQuery('#saveNewType').after('<p>'+data.message+'</p>');
			}
        },
        error: function(error) {
            console.log(error);
			jQuery('.imageLoaderPanier').remove();
			jQuery('#saveNewType').after('<p>Une erreur s\'est produite lors de l\'exécution du script</p>');
        }
    });  
}

function deleteType(typeToDelete){
    jQuery.ajax({
       url: 'index.php?ajaxID=EAnnuaires',
        type: 'POST',
        dataType : "json",
        data: {
            request:{typeToDelete:typeToDelete},
			type:'deleteType'
        },
        success: function(data){
			jQuery('.imageLoaderPanier').remove();
			
			if(data.message != 0){
				jQuery('#'+typeToDelete).after('<p>'+data.message+'</p>');
			} else {
				// On cache supprime le type supprimer de la liste
				jQuery('#'+data.typeToDelete).parents('li').remove();
				
				// On supprime aussi le bloc associé.
				idTypeToDeleteArray = typeToDelete.split('-');
				idTypeToDelete = idTypeToDeleteArray[1];
				jQuery('.confTypeNum'+idTypeToDelete).remove();
			}
        },
        error: function(error, typeToDelete) {
            console.log(error);
			jQuery('.imageLoaderPanier').remove();
			jQuery('#typeToDelete').after('<p>Une erreur s\'est produite lors de l\'exécution du script</p>');
        }
    });  
}

function generateDetailConf(selector){
	if(!selector){
		selector = '.itemTypeCourant';
	}
	
	jQuery(selector).each(function(){
		jQuery(this).after(imageLoaderPanier); 
		idType = jQuery(this).attr('id');
		jQuery.ajax({
		   url: 'index.php?ajaxID=EAnnuaires',
			type: 'POST',
			dataType : "json",
			data: {
				request:{idType:idType},
				type:'generateDetailConf'
			},
			success: function(data){
				// console.log('#'+data.idType);
				jQuery('#'+data.idType).append(data.content);
				jQuery('.imageLoaderPanier').remove();
				eventsAfterConfGeneration();	
			},
			error: function(error) {
				console.log('ERROR -> '+error);
				jQuery(this).html('<p>La configuration de ce type n\a pas pu être généré, la requete AJAX semble avoir echoué</p>');
				jQuery('.imageLoaderPanier').remove();
			}
		});  
	});
}

function generateDetailConfForm(selector){
	if(!selector){
		selector = '.itemTypeCourant';
	}

	jQuery(selector).each(function(){
		jQuery('.currentTypeConf').html(imageLoaderPanier); 
		idType = jQuery(this).attr('id');
		jQuery.ajax({
		   url: 'index.php?ajaxID=EAnnuaires',
			type: 'POST',
			dataType : "json",
			data: {
				request:{
					idType:idType,
					source: 'form'
				},
				type:'generateDetailConf'
			},
			success: function(data){
				// console.log('#'+data.idType);
				jQuery('#'+data.idType).append(data.content);
				jQuery('.imageLoaderPanier').remove();
				eventsAfterFormGeneration();
			},
			error: function(error) {
				console.log('ERROR -> '+error);
				jQuery(this).html('<p>La configuration de ce type n\a pas pu être généré, la requete AJAX semble avoir echoué</p>');
				jQuery('.imageLoaderPanier').remove();
			}
		});  
	});
}

function saveConf(idType){
	updateArray = {};
	
	jQuery('.confTypeNum'+idType+' input[type=text]').each(function(){		
		updateArray[jQuery(this).attr('id')] = jQuery(this).val();
	});
	
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			request:{
				updateArray:updateArray,
				idType:idType
			},
			type:'updateLabelTab'
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#saveConf-'+data.idType).append(data.content);
		},
		error: function(error) {
			console.log('ERROR -> '+error);
			jQuery('.imageLoaderPanier').remove();
			jQuery(this).html('<p>La sauvegarde des labels n\'a pas fonctionné, la requete AJAX semble avoir echoué</p>');
		}
	}); 
}

function addNewField(newFieldName,newFieldType,countSorting){
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			request:{
				newFieldName:newFieldName,
				newFieldType:newFieldType,
				sorting:countSorting
			},
			type:'addNewField'
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#confTypeNum-'+data.idType).append(data.content);
			eventsAfterConfGeneration();	
			
			jQuery('.ListeChampBdd > select').removeAttr("disabled");
		},
		error: function(error) {
			console.log('ERROR -> '+error);
			jQuery('.imageLoaderPanier').remove();
			jQuery(this).html('<p>Le champ n\a pas pu être ajouté, la requete AJAX semble avoir echoué</p>');
		}
	});  
}

function increaseSorting(idField,item){
	var itemParent = item.parents('.fieldConf');
	
	nextItem = itemParent.next().find('.increaseSorting').attr('id').split('-');
	
	if(nextItem.length > 0){
		nextIdField = nextItem[1];
		itemParent.next().after(itemParent);
		
		jQuery.ajax({
		   url: 'index.php?ajaxID=EAnnuaires',
			type: 'POST',
			dataType : "json",
			data: {
				request:{
					lowSorting:idField,
					highSorting:nextIdField
				},
				type:'changeSorting'
			},
			success: function(data){
			},
			error: function(error) {
				console.log('ERROR -> '+error);
				jQuery(this).html('<p>La modification du sorting n\'a pas fonctionné, la requete AJAX semble avoir echoué</p>');
			}
		});  
	}
}

function decreaseSorting(idField,item){
	var itemParent = item.parents('.fieldConf');

	prevItem = itemParent.prev().find('.increaseSorting').attr('id').split('-');
	
	if(prevItem.length > 0){
		prevIdField = prevItem[1];
		itemParent.prev().before(itemParent);
			
		jQuery.ajax({
		   url: 'index.php?ajaxID=EAnnuaires',
			type: 'POST',
			dataType : "json",
			data: {
				request:{
					lowSorting:prevIdField,
					highSorting:idField
				},
				type:'changeSorting'
			},
			success: function(data){
			},
			error: function(error) {
				console.log('ERROR -> '+error);
				jQuery(this).html('<p>La modification du sorting n\'a pas fonctionné, la requete AJAX semble avoir echoué</p>');
			}
		}); 
	}
}

function deleteChamp(idField){
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			request:{
				fieldToDelete:idField,
			},
			type:'deleteField'
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#deleteChamp-'+data.idField).parents('.fieldConf').remove();
		},
		error: function(error) {
			jQuery('.imageLoaderPanier').remove();
			console.log('ERROR -> '+error);
			jQuery(this).html('<p>La suppression du champ n\'a pas fonctionné, la requete AJAX semble avoir echoué</p>');
		}
	}); 
}

function getConfField(fieldName,idSelect){
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			request:{
				fieldName:fieldName,
				idSelect:idSelect
			},
			type:'getTcaConf'
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#'+data.idSelect).next('.infoChamp').html(data.content); 
			jQuery('#'+data.idSelect).next('.infoChamp').show();
		},
		error: function(error) {
			jQuery('.imageLoaderPanier').remove();
			console.log('ERROR -> '+error);
			jQuery('.infoChamp').show();
			jQuery('.infoChamp').html('<p>Impossible d\'afficher la conf de champ, la requete AJAX semble avoir echoué</p>');
		}
	}); 
}

function generateConf(){
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			type:'generateConf'
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			jQuery('#generateConf').after(data.content); 
		},
		error: function(error) {
			jQuery('.imageLoaderPanier').remove();
			console.log('ERROR -> '+error);
			jQuery('#generateConf').after('<p>La génération de la conf a échoué, la requete AJAX semble avoir échoué.</p>'); 
		}
	}); 
}

function generateFormTemplate(){
	var arrayFields = [];
	jQuery('.fieldConf .firstLineConf').not('.disabledField').each(function(){
		arrayFields.push(jQuery(this).data('fieldname'));
	});
	jQuery.ajax({
	   url: 'index.php?ajaxID=EAnnuaires',
		type: 'POST',
		dataType : "json",
		data: {
			type:'generateFormTemplate',
			request:{
				arrayFields:arrayFields
			},
		},
		success: function(data){
			jQuery('.imageLoaderPanier').remove();
			// jQuery('#finalTemplate').html(data.content); 
			jQuery('#finalTemplate').text(data.content); 
		},
		error: function(error) {
			jQuery('.imageLoaderPanier').remove();
			console.log('ERROR -> '+error);
			jQuery('#generateConf').after('<p>La génération du formulaire a échoué, la requete AJAX semble avoir échoué.</p>'); 
		}
	});
}