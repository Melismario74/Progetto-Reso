function fhAggregation_search() {
	
	var tbodyElement = '#fhAggregation_results_table tbody';
	var tfootElement = '#fhAggregation_results_table tfoot td';
	var progressElement = '#fhAggregation .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=aggregation&task=jsonGetAggragableArticles&type=json',
		{
			
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				articles = result.articles;
				articlesCount = result.result;
				
				if (articlesCount > 0) {
					jQuery('#fhAggregation_aggregateBtn').show();
				} else {
					jQuery('#fhAggregation_aggregateBtn').hide();
				}
				
				for (var i = 0; i < articlesCount; i++) {
				    var articleData = articles[i];
				    
				    var rowHtml = 
				    	'<tr class="form-inline">' +
				    		'<td style="text-align: center"><input type="checkbox" class="fhAggregation_articleId" value="' + articleData.article_id + '" /></td>' +
				    		'<td style="text-align: right">' + 
				    			articleData.article_code +
				    		'</td>' +
				    		'<td>' +
				    			'<a href="#" onclick="fhAggregation_selectArticle(' + articleData.article_id + '); return false">' + articleData.name + '</a>' +
				    		'</td>' +
				    		'<td style="text-align: right">' +
				    			articleData.quantity_units +
				    		'</td>' +
				    		'<td style="text-align: right">' +
				    			articleData.package_units +
				    		'</td>' +
				    		'<td style="text-align: right"><input type="text" style="width: 50px" id="fhAggregation_quantity_' + articleData.article_id + '" value="' +
				    			articleData.packages_available + '" />' +
				    		'</td>' +
				    	'</tr>';
				    
				    jQuery(tbodyElement).append(rowHtml);
				}
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhAggregation_selectArticle(articleId) {

	var params = 'articleId=' + articleId;
	
	var popupArticle = DMPopup.getInstance({
		name: 'popupArticle',
		includeCallback: function () {
			this.openPopup('open', params);
		}
	});

}

function fhAggregation_aggregate() {

	var progressElement = '#fhAggregation .search .progress';
	
	jQuery(progressElement).slideDown();
	
	var aggregationData = [];
	jQuery('.fhAggregation_articleId').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.article_id = jQuery(this).val();
			myItem.packages = jQuery('#fhAggregation_quantity_' + myItem.article_id).val();
			aggregationData.push(myItem);
		}
	});
	
	if (aggregationData.length > 0) {
		jQuery.post(
			'index.php?controller=aggregation&task=jsonAggregateArticles&type=json',
			{
				"aggregationData": JSON.stringify(aggregationData)
			},
			function (data) {
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert('Aggregati con successo ' + result.data.success + ' articoli (errori: ' + result.data.fail + ')');
					window.open(result.data.label_url, '_blank');
					jQuery(progressElement).slideUp();
					fhAggregation_search();
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
					jQuery(progressElement).slideUp();
				}
			}
		);
		
	} else {
		alert('Nessun articolo selezionato');
		jQuery(progressElement).slideUp();
	}
	
}