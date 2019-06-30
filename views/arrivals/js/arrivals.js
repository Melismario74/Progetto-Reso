function fhArrivals_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var arrival_archived = jQuery('#fhArrivals_filterArrivalArchived').val();
	
	if (arrival_archived == "Si") {
		arrival_archived = 1;
	} else if (arrival_archived == "No"){
		arrival_archived = 0;
	} else {
		arrival_archived = -1;
	}
	
	var tbodyElement = '#fhArrivals_results_table tbody';
	var tfootElement = '#fhArrivals_results_table tfoot td';
	var progressElement = '#fhArrivals .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=arrival&task=jsonGetArrivals&type=json',
		{
			"arrivalDateFrom": jQuery('#fhArrivals_filterArrivalDateFrom').val(),
			"arrivalDateTo": jQuery('#fhArrivals_filterArrivalDateTo').val(),
			"page": currentPage,
			"arrival_archived": arrival_archived,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				totalPages = result.result;
				
				if (totalPages > 0) {
					var arrivals = result.arrivals;
					var arrivalsCount = arrivals.length;
					
					for (var i = 0; i < arrivalsCount; i++) {
						var arrivalData = arrivals[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center"> <a href="#" onclick="fhArrivals_openArrival(' + arrivalData.arrival_id + '); return false;">' + arrivalData.arrival_code_str + '</a></td>' +
								'<td style="text-align: center">' + arrivalData.arrival_date_str + '</td>' +
								'<td style="text-align: center">' + arrivalData.vector_name + '</td>' +
								'<td style="text-align: center">' + arrivalData.subject + '</td>' +
								'<td style="text-align: center">' + arrivalData.nLdv + '</td>' +
								'<td style="text-align: center">' + arrivalData.nDdt + '</td>' +
								'<td style="text-align: center">' + arrivalData.notes + '</td>' +
								'<td style="text-align: center">' + arrivalData.arrival_archived_str + '</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhArrivals_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhArrivals_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}	
				
				footHtml += '<select id="fhArrivals_selectionPage" name="fhArrivals_selectionPage" class="fhArrivals_selectionPage" onchange="fhArrivals_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhArrivals_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhArrivals_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
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

function fhArrivals_openArrival(arrivalId) {
	
	window.location = 'index.php?controller=arrival&view=arrival&arrivalId=' + arrivalId;
	
}