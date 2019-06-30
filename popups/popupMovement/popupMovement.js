var popupMovement_data = {};

function popupMovement_loadArticle(articleId) {
	
	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				popupMovement_data.article = result.article;
				
				//Carico i dati dell'articolo
				jQuery('#popupMovement_articleName').html(popupMovement_data.article.name);
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function popupMovement_saveMovement() {
	
	if (!jQuery('#popupMovement_form').valid()) {
		return false;
	}
	
	var quantityUnits = jQuery('#popupMovement_editUnits').val();
	var quantityPackages = jQuery('#popupMovement_editPackages').val();
	var stockFromId = jQuery('#popupMovement_editStockFrom').val();
	var stockToId = jQuery('#popupMovement_editStockTo').val();
	var chargelistId = jQuery('#popupMovement_editChargelist').val();
	
	//Alcuni check
	if ((stockFromId < 1) && (stockToId < 1)) {
		alert('Occorre indicare almeno un magazzino di partenza o un magazzino di destinazione');
		return false;
	}
	
	if ((quantityUnits < 1) && (quantityPackages < 1)) {
		alert('Occorre indicare la quantità');
		return false;
	}
	
	jQuery.post(
		'index.php?controller=stock&task=jsonSaveMovement&type=json',
		{
			"articleId": popupMovement_data.article.article_id,
			"quantityUnits": quantityUnits,
			"quantityPackages": quantityPackages,
			"stockFromId": stockFromId,
			"stockToId": stockToId,
			"chargelistId": chargelistId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				alert('Movimento salvato');
				DMPopup.successPopup('popupMovement');
		        jQuery('#popupMovement').modal("hide");
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}