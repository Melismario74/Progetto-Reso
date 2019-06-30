function fhStock_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var tbodyElement = '#fhStock_results_table tbody';
	var tfootElement = '#fhStock_results_table tfoot td';
	var progressElement = '#fhStock .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
		'index.php?controller=article&task=jsonGetArticles&type=json',
		{
			"articleCode": jQuery('#fhStock_filterArticleCode').val(),
			"eanCode": jQuery('#fhStock_filterEanCode').val(),
			"name": jQuery('#fhStock_filterName').val(),
			"inStockOnly": 1,
			"page": currentPage,
			"getStockData": 1,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				totalPages = result.result;
				
				if (totalPages > 0) {
					articles = result.articles;
					articlesCount = articles.length;
					
					for (var i = 0; i < articlesCount; i++) {
						var articleData = articles[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: right">' + articleData.article_code + '</td>' +
								'<td><a href="#" onclick="fhStock_selectArticle(' + articleData.article_id + '); return false">' + articleData.name + '</a></td>' +
								'<td style="text-align: right">' + articleData.stock[1].total_units +'</td>' +
								'<td style="text-align: right">' + articleData.stock[1].total_packages + '</td>' +
								'<td style="text-align: right">' + articleData.stock[3].total_units +'</td>' +
								'<td style="text-align: right">' + articleData.stock[2].total_units + '</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhStock_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhStock_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}
				
				footHtml += '<select id="fhStock_selectionPage" name="fhStock_selectionPage" class="fhStock_selectionPage" onchange="fhStock_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhStock_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhStock_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
				}
				
				jQuery(tfootElement).html(footHtml);
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhStock_print() {

	jQuery.post(
		'index.php?controller=stock&task=jsonPrintStock&type=json',
		{
			"articleCode": jQuery('#fhStock_filterArticleCode').val(),
			"eanCode": jQuery('#fhStock_filterEanCode').val(),
			"name": jQuery('#fhStock_filterName').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.print_url, '_blank');				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function fhStock_export() {

	jQuery.post(
		'index.php?controller=stock&task=jsonExportStock&type=json',
		{
			"articleCode": jQuery('#fhStock_filterArticleCode').val(),
			"eanCode": jQuery('#fhStock_filterEanCode').val(),
			"name": jQuery('#fhStock_filterName').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.export_url, '_blank');				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}


function fhStock_exportCSV() { 

	
	jQuery.post(
		'index.php?controller=stock&task=jsonExportStockCSV&type=json',
		{
			"articleCode": jQuery('#fhStock_filterArticleCode').val(),
			"eanCode": jQuery('#fhStock_filterEanCode').val(),
			"name": jQuery('#fhStock_filterName').val(),
			"inStockOnly": 1
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.export_url, '_blank');				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function fhStock_showUdms() {
	
	var popupUdmList = DMPopup.getInstance({
		name: 'popupUdmList',
		includeCallback: function () {
			this.openPopup('open', '');
		}
	});
	
}

function fhStock_selectArticle(articleId) {

	var params = 'articleId=' + articleId;
	
	var popupArticle = DMPopup.getInstance({
		name: 'popupArticle',
		includeCallback: function () {
			this.openPopup('open', params);
		}
	});

}