$.noConflict();
imageLoaderPanier = '<img class="imageLoaderPanier" src="../typo3conf/ext/e_annuaires/Resources/Public/Icons/loader.gif" style="margin-top:10px;margin-left:5px;background-color:#000;border-radius:5px;padding:10px;" />';

$(document).ready(function(){
	$('.form_inline').each(function(index){
		idSplit = $(this).attr('id').split('__');
		field = idSplit[2];
		
		$(this).html(imageLoaderPanier);
		generateInlineForm(field);
	});	
	$('.removeImage').click(function(){
		imageToRemove = $(this).parent().find('img');
		removeImage(imageToRemove);
	});
	initForm();
});
function initForm(){			
	$('.submit_inline_form input[type=button].submitButton').click(function(e){
		submitVal = $(this).parents('.submit_inline_form').find(".inlineFormField:input").serializeArray();			
		submitInlineForm(submitVal,this);
		e.stopImmediatePropagation();
	});
	$('.submit_inline_form input[type=button].submitButtonFal').click(function(e){
		files = $(this).parents('.submit_inline_form').find(".inlineFormField:file");
		if(files.length > 0){
			$(files).each(function(){
				itemFile = this;
				uploadInlineImage($(this)[0].files[0], itemFile);
			});		
		}
		e.stopImmediatePropagation();
	});
	$('.removeInline').click(function(e){
		idArray = $(this).attr('id').split('-');
		removeInlineItem(idArray[1],idArray[2]);
		e.stopImmediatePropagation();
	});
	$('#edit_annuaire_fe input[type=submit]').click(function(e){
		// On vide tous les inputs des champ des inlines pour permettre la soumission sans encombre des champs des fiches
		$(this).parents('#edit_annuaire_fe').find('.form_inline').each(function(index){
			$(this).find(":input").attr('name','');
		});
		e.stopImmediatePropagation();
	});
	$('#field_mandat_type').change(function(e){
		$('#form__inline__mandat').html(imageLoaderPanier);
		changeMandat($(this).val());
		e.stopImmediatePropagation();
	});
	$('.field_image').change(function(e){
		createNextUploadField(this);
		e.stopImmediatePropagation();
	});
	
	$('.removeInlineImage').click(function(e){
		imageToRemove = $(this).parent().find('img');
		$(this).parent().html(imageLoaderPanier);
		if(imageToRemove.length > 0){
			removeInlineImage(imageToRemove);
		}
		e.stopImmediatePropagation();
	});
}
function createNextUploadField(elem){
	if($(elem).val() != ''){
		currentName = $(elem).attr('name');
		currentNumber = currentName.substring(currentName.length-2,currentName.length-1);
		newNumber = parseInt(currentNumber)+1;
		
		if($('input[name*=file-'+newNumber+']').length < 1){
			currentField = $(elem).parent().clone();
			currentFieldHtml = $(elem).parent().html();	
			newField = replaceAll(currentFieldHtml,'file-'+currentNumber,'file-'+newNumber);
			
			currentField.html(newField);
			$(elem).parent().after(currentField);		
			initForm();
		}
	}
}
function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function replaceAll(str, find, replace) {
  return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
function uploadInlineImage(currentFile, elem){
	var idCurrent = $(currentFile).parents('.submit_inline_form').attr('id');
	
	imageFormData = new FormData($(currentFile).parents('.submit_inline_form').html());
	imageFormData.append($(elem).attr('name'),currentFile);
	
	$(currentFile).parents('.submit_inline_form').html(imageLoaderPanier);
	
    $.ajax({
		url: '?type=2809884',
        type: 'POST',    
		processData: false,  // indique à jQuery de ne pas traiter les données
		contentType: false,   // indique à jQuery de ne pas configurer le contentType
        data: imageFormData,
        success: function(data){
			infoFile = $(elem).parent().find(":input").serializeArray();			
			$.ajax({
				url: '?type=2809885',
				type: 'POST',    
				data: {
					tx_eannuaires_pi1: {
						fileInfo: infoFile
					}
				},
				success: function(result){
					$('.imageLoaderPanier').remove();
					$(elem).parent().prepend(data);
					$(elem).parent().find('input[type=text]').val('');
					initForm();
				},
				error: function(error) {
					console.log('ERROR -> '+error);
					$('.imageLoaderPanier').remove();
				}
			});
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function submitInlineForm(submitVal,elem){
	idCurrent = $(elem).parent('.submit_inline_form').attr('id');
	$('#'+idCurrent).html(imageLoaderPanier);

	console.log(elem);
	console.log(idCurrent);
	
    $.ajax({
		url: '?type=2809881',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				dataForm:submitVal
			},
        },
		// data:submitVal,
        success: function(data){
			$('.imageLoaderPanier').remove();
			$('#'+idCurrent).append(data);
			$(".inlineFormField:input").val('');
			initForm();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function generateInlineForm(field){
    $.ajax({
       url: '?type=2809880',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				field:field
			}
        },
        success: function(data){
			$('.imageLoaderPanier').remove();
			$('#form__inline__'+field).append(data);
			initForm();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function changeMandat(value){
    $.ajax({
       url: '?type=2809883',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				typeMandat:value
			}
        },
        success: function(data){
			$('.imageLoaderPanier').remove();
			$('#form__inline__mandat').append(data);
			initForm();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function removeInlineItem(table,item){
	currentItem = $('#remove-'+table+'-'+item).parents('li');
	currentItem.html(imageLoaderPanier);
    $.ajax({
		url: '?type=2809882',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				table:table,
				item:item,
			},
        },
        success: function(){
			$('.imageLoaderPanier').remove();
			currentItem.remove();
			initForm();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function removeImage(imageToRemove){
	dataImage = imageToRemove.data('uidimage');
	dataFiche = $('#uidFiche').val();
	imageToRemove.parent().html(imageLoaderPanier);
    $.ajax({
		url: '?type=2809886',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				imageToRemove:dataImage,
				uidFiche:dataFiche
			},
        },
        success: function(){
			$('.imageLoaderPanier').remove();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}
function removeInlineImage(imageToRemove){
	dataImage = imageToRemove.data('uidimage');
	typeInline = imageToRemove.data('type');
	uidInline = imageToRemove.data('uidinline');
	$.ajax({
		url: '?type=2809887',
        type: 'POST',
        data: {
			tx_eannuaires_pi1: {
				imageToRemove:dataImage,
				uidInline:uidInline,
				typeInline:typeInline
			},
        },
        success: function(){
			$('.imageLoaderPanier').remove();
        },
        error: function(error) {
            console.log('ERROR -> '+error);
			$('.imageLoaderPanier').remove();
        }
    });
}