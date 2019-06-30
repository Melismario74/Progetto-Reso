function popupArticleSearch_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var tbodyElement = '#popupArticleSearch_results_table tbody';
	var tfootElement = '#popupArticleSearch_results_table tfoot td';
	var progressElement = '#popupArticleSearch .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=article&task=jsonGetArticles&type=json',
		{
			"articleCode": jQuery('#popupArticleSearch_filterArticleCode').val(),
			"eanCode": jQuery('#popupArticleSearch_filterEanCode').val(),
			"name": jQuery('#popupArticleSearch_filterName').val(),
			"getArticleImage": 1,
			"page": currentPage,
			"limit": 10
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
								'<td style="text-align: right">' + 
									articleData.article_code +
								'</td>' +
								'<td style="text-align: center">' +
									'<a href="' + articleData.image_url + '"><img src="' + articleData.image_url + '" style="height: 40px;" /></a>' +
								'</td>' +
								'<td>' +
									'<a href="#" onclick="popupArticleSearch_selectArticle(' + articleData.article_id + '); return false;">' + articleData.name + '</a>' +
								'</td>' +
								'<td>' +
									articleData.package_units +
								'</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" onclick="popupArticleSearch_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> Precedente</a>';
				}
				
				footHtml += 'Pagina ' + currentPage + ' di ' + totalPages;
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" onclick="popupArticleSearch_search(' + (currentPage + 1) + '); return false;">Successiva <i class="icon-arrow-r"></i></a>';
				}
				
				jQuery(tfootElement).html(footHtml);
				
				jQuery(tbodyElement).imageZoom();
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function popupArticleSearch_selectArticle(articleId) {

	DMPopup.successPopup('popupArticleSearch', articleId);
	jQuery('#popupArticleSearch').modal("hide");

}