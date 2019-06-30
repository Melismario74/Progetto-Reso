var fhLogistics_data = {};
var fhLogistics_canSelect = 0;
var fhLogistics_canPrint = 0;



/**
	Apre il popup di ricerca articoli
**/
function fhLogistics_articleSearch(eanCode) {
	
	
	var myParams = "";
	if (eanCode != undefined) {
		myParams += "eanCode=" + eanCode;
	}
	
	var fhLogisticsSearch = DMPopup.getInstance({
	    name: 'popupArticleSearch',
	    includeCallback: function () {
	    	this.openPopup('open', myParams);
	    },
	    onSuccess: function (articleId) {
	    	fhLogistics_articleSelect(articleId);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhLogistics_articleSelect(articleId) {
	
	var tbodyElement = '#fhLogistics_article_data tbody';
	
	jQuery('#fhLogistics_article').slideDown();
	jQuery('#fhLogistics_stocks').slideDown();	
	jQuery('#fhLogistics_article_data').slideUp();
	jQuery('#fhLogistics_article_loading').slideDown();
	
	jQuery(tbodyElement).html("").hide();
	
	
	
	jQuery.post(
		'index.php?controller=logistics&task=jsonGetItemUdms&type=json',
		{
			"articleId": articleId
			
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			if (result != false) {	
			
				if (result.result > 0) {
					
					fhLogistics_canSelect= 1;
					fhLogistics_canPrint= 1;
					
					var udms = result.udms;
					var udmsCount = udms.length;
					

                    var lastUdmCode = '';
					
					for (var i = 0; i < udmsCount; i++) {
						var udmData = udms[i];

                        if (udmData.udm_code == lastUdmCode) {
                            udmData.udm_code = '';
                            var showSelect = false;
                        } else {
                            lastUdmCode = udmData.udm_code;
                            var showSelect = true;							
                        }
						
			
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.article_code + '</td>' +
								'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.quantity_units + '</td>' +
								'<td  id="fhLogistics_ubicazione_' + udmData.udm_code + '" style="text-align: center; vertical-align: middle;">' + udmData.ubicazione + '</td>';
								
						if (fhLogistics_canSelect && showSelect) {
							rowHtml += '<td style="text-align: center"><button class="btn btn-mini btn-primary" id="fhLogistics_btnSelectUdm_' + udmData.udm_code + '" onclick="fhLogistics_selectUdm(' + udmData.udm_code + '); return false;">Seleziona</button></td>';
						} else if (fhLogistics_canSelect) {
                            rowHtml += '<td></td>';
                        }

                        if (fhLogistics_canPrint && showSelect) {
                            rowHtml += '<td style="text-align: center"><button class="btn btn-mini" id="fhLogistics_btnPrintUdm_' + udmData.udm_code + '" onclick="fhLogistics_printUdm(' + udmData.udm_code + '); return false;">Stampa</button></td>';
                        } else if (fhLogistics_canPrint) {
                            rowHtml += '<td></td>';
                        }
						rowHtml += '<td style="text-align: center; vertical-align: middle;"><button class="btn btn-mini btn-danger" onclick="fhLogistics_deleteUdm(' +  udmData.udm_code + '); return false;"><i class="icon-white icon-remove"></i></button></td>';
						rowHtml += '</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					jQuery(tbodyElement).slideDown();
					jQuery('#fhLogistics_article_data').slideDown();
					jQuery('#fhLogistics_article_loading').slideUp();
					
				} else {
					alert("NON SONO PRESENTI UDM CON QUESTO ARTICOLO");
					jQuery(tbodyElement).slideDown();
					jQuery('#fhLogistics_article_data').slideDown();
					jQuery('#fhLogistics_article_loading').slideUp();
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
			}
		}
		
	);
	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId,
			"getStocksData": 1,
			"getUdmsData": 1
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhLogistics_data.article = result.article;
			
				//Carico i dati dell'articolo
				jQuery('#fhLogistics_articleCode').html(fhLogistics_data.article.article_code);
				jQuery('#fhLogistics_eanCode').html(fhLogistics_data.article.ean_code);
				
				var stocks = fhLogistics_data.article.stocks;
				var stocksCount = stocks.length;
				
				var tbodyElement = '#fhLogistics_articleStocks tbody';
				
				jQuery(tbodyElement).html("");
				
				for (var i = 0; i < stocksCount; i++) {
					var stockData = stocks[i];
					
					var rowHtml = 
						'<tr>' +
							'<td style="text-align: left">' + stockData.name + '</td>' +
							'<td style="text-align: center">' +
								stockData.total_units +
							'</td>' +
						'</tr>';
					
					jQuery(tbodyElement).append(rowHtml);
				}	
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}



function fhLogistics_deleteUdm(udmCode) {

	if (confirm("Questa operazione non è annullabile! Sicuro di voler eliminare questa UDM?")) {
		jQuery.post(
			'index.php?controller=logistics&task=jsonDeleteUdm&type=json',
			{
				"udmCode": udmCode
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert("UDM eliminata");
					window.location.reload();
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}
		);
	}
	
}

function fhLogistics_barcodeSearch() {
	
	jQuery('#fhLogistics_scan_loading').slideDown();
	
	var barcode = jQuery('#fhLogistics_scan_code').val();
	
	jQuery.post(
		'index.php?controller=buono&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
					
					if (result.data.article_id > -1) {
							fhLogistics_articleSelect(result.data.article_id);
						} else {
						fhLogistics_articleSearch(barcode);
					}
				
				jQuery('#fhLogistics_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhLogistics_scan_loading').slideUp();
			}
		}
		
	);
	
}


/* 
	---- fase 2 by Mario ----
*/

function fhLogistics_udmInputCode() {

    var udmCode = jQuery('#fhLogistics_udm_input_code').val();

    fhLogistics_selectUdm(udmCode);

}

function fhLogistics_udmSearch() {

    var fhLogistics = DMPopup.getInstance({
        name: 'fhLogistics',
        includeCallback: function () {
            this.openPopup('open', 'canSelect=1&canPrint=1');
        },
        onSuccess: function (udmCode) {
            fhLogistics_selectUdm(udmCode);
            jQuery('#fhLogistics').modal("hide");
        }
    });

}

function fhLogistics_udmNew() {

    jQuery('#fhLogistics_udm .progress').slideDown();
    jQuery('#fhLogistics_udm .interaction').slideUp();

    jQuery.post(
        'index.php?controller=logistics&task=jsonCreateUdm&type=json',
        {
        },
        function (data) {
            var result = DMResponse.validateJson(data);

            if (result != false) {

                if (result.result >= 0) {

                    fhLogistics_selectUdm(result.udm.udm_code);

                } else if (result.result == -404) {

                    alert('UDM non trovata');
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();

                } else {
                    alert("Si è verificato un errore (" + result.result + "): " + result.description);
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();
                }
            } else {
                alert("Si è verificato un errore (" + result.result + "): " + result.description);
                jQuery('#fhLogistics_udm .progress').slideUp();
                jQuery('#fhLogistics_udm .interaction').slideDown();
            }
        }
    );

}


function fhLogistics_ibdNew() {

    jQuery('#fhLogistics_udm .progress').slideDown();
    jQuery('#fhLogistics_udm .interaction').slideUp();

    jQuery.post(
        'index.php?controller=logistics&task=jsonCreateIbd&type=json',
        {
        },
        function (data) {
            var result = DMResponse.validateJson(data);

            if (result != false) {

                if (result.result >= 0) {

                    fhLogistics_selectUdm(result.udm.udm_code);

                    
                } else if (result.result == -404) {

                    alert('UDM non trovata');
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();

                } else {
                    alert("Si è verificato un errore (" + result.result + "): " + result.description);
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();
                }
            } else {
                alert("Si è verificato un errore (" + result.result + "): " + result.description);
                jQuery('#fhLogistics_udm .progress').slideUp();
                jQuery('#fhLogistics_udm .interaction').slideDown();
            }
        }
    );

}

function fhLogistics_selectUdm(udmCode) {
	
	
    jQuery('#fhLogistics_udm .progress').slideDown();
	 jQuery('#fhLogistics_scan').slideUp();
    jQuery('#fhLogistics_udm .interaction').slideUp();
	var ubicazione = jQuery('#fhLogistics_ubicazione_' + udmCode ).html();
	if (ubicazione == undefined) {
		ubicazione = '';
	} 		
	
	
	 jQuery.post(
        'index.php?controller=logistics&task=jsonGetUdm&type=json',
        {
            "udmCode": udmCode,
			"ubicazione": ubicazione
        },
        function (data) {
            var result = DMResponse.validateJson(data);

            if (result != false) {

                if (result.result >= 0) {

                    fhLogistics_data.udmCode = udmCode;
					fhLogistics_data.ubicazione = ubicazione;
                    fhLogistics_data.udmArticlesStored = result.data.articles;
                    fhLogistics_startUDMFilling();

                } else if (result.result == -404) {

                    alert('UDM non trovata');
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();

                } else {
                    alert("Si è verificato un errore (" + result.result + "): " + result.description);
                    jQuery('#fhLogistics_udm .progress').slideUp();
                    jQuery('#fhLogistics_udm .interaction').slideDown();
                }
            } else {
                alert("Si è verificato un errore (" + result.result + "): " + result.description);
                jQuery('#fhLogistics_udm .progress').slideUp();
                jQuery('#fhLogistics_udm .interaction').slideDown();
            }
        }
    );

}

function fhLogistics_startUDMFilling() {
	
    jQuery('#fhLogistics_articles_udmCode').html("CODICE UDM: " + fhLogistics_data.udmCode);
	
    //Carico la tabella degli already stored...
	var rowHtml =
			'<td>' +
					'<button type="button" class="btn btn-danger" onclick="fhLogistics_deleteUdm(' + fhLogistics_data.udmCode + '); return false;">Elimina UDM creata</button>' +
			'</td>';
	jQuery('.first').append(rowHtml);
	
    jQuery('#fhLogistics_articles_storedArticles_table').html('');
    for (var i = 0; i < fhLogistics_data.udmArticlesStored.length; i++) {				

        var currentArticle = fhLogistics_data.udmArticlesStored[i];
        var rowHtml =
            '<tr>' +
                '<td id="article_stored_code">' + currentArticle.articleCode  + '</td>' +
                '<td>' + currentArticle.name  + '</td>' +
				'<td><input class="span2" id="fhlogistics_ubicazione_input_code_'+ fhLogistics_data.udmCode + '"  type="text" value = "'+ fhLogistics_data.ubicazione  + '"/></td>' +
                '<td>' + currentArticle.quantity_units  + '</td>' +
            '</tr>';

        jQuery('#fhLogistics_articles_storedArticles_table').append(rowHtml);
		
    }

    if (fhLogistics_data.udmArticlesStored.length == 0) {
		 var rowHtml =
			'<tr>' + 
				'<td colspan="4">Indica ubicazione </td>' + 
				'<td><input class="span2" id="fhlogistics_ubicazione_input_code_'+ fhLogistics_data.udmCode + '"  type="text" value = "'+ fhLogistics_data.ubicazione  + '"/></td>' +
			'</tr>';
			
        jQuery('#fhLogistics_articles_storedArticles_table').append(rowHtml);
    }

    //Resetto i sessionArticles
    fhLogistics_data.udmArticlesSession = new Array();
    jQuery('#fhLogistics_articles_sessionArticles_table').html('');
    fhLogistics_refreshSessionArticlesTable();
    jQuery('#fhLogistics_udm').slideUp();
    jQuery('#fhLogistics_articles').slideDown();	

    jQuery('#fhLogistics_article_input_code').focus();

}

function fhLogistics_articleInputCode() {

    jQuery('#fhLogistics_articles_articleInput .progress').slideDown();
    jQuery('#fhLogistics_articles_articleInput .interaction').slideUp();
    jQuery('#fhLogistics_scan_loading').slideDown();
	
	var udmCode = fhLogistics_data.udmCode;
	var stockId = jQuery('#fhLogistics_articles_udmCode').html().substring(12,13);
	
	
	if (stockId === '1') {
		var getStocksData = 1;
	} else {
		var getStocksData = 3;
	}
	
	
    var articleCode = jQuery('#fhLogistics_article_input_code').val();

    jQuery.post(
        'index.php?controller=article&task=jsonLoadArticle&type=json',
        {
            "articleCode": articleCode,
            "getUdmsData": 1,
			"udmCode": udmCode,
            "getStocksData": getStocksData
        },
        function (data) {
            var result = DMResponse.validateJson(data);

            if (result != false) {

                if (result.result >= 0) {

                    var articleData = result.article;

                    if (fhLogistics_data.udmArticlesSession[articleData.article_id] != undefined) {
                        var articleSession = fhLogistics_data.udmArticlesSession[articleData.article_id];
                    } else {
                        fhLogistics_data.udmArticlesSession[articleData.article_id] = articleData;
                        var articleSession = fhLogistics_data.udmArticlesSession[articleData.article_id];
                    }

                    if (isNaN(articleSession.quantity)) {
                        articleSession.quantity = 0;
                    }
					
                    if ((articleSession.quantity + 1) > (articleData.stocks[getStocksData-1].total_units - articleData.dispatched[getStocksData-1].total_units)) {
                        alert('Non ci sono sufficienti cartoni buoni di questo articolo per completare questa operazione.');
                        jQuery('#fhLogistics_articles_articleInput .progress').slideUp();
                        jQuery('#fhLogistics_articles_articleInput .interaction').slideDown();
                        jQuery('#fhLogistics_scan_loading').slideUp();
                        return false;
                    }

                    articleSession.quantity += 0;

                    jQuery('#fhLogistics_articles_lastArticle').html('Ultimo articolo inserito: ' + articleSession.name + '; totale quantità provvisoria: ' + articleSession.quantity);

                    fhLogistics_refreshSessionArticlesTable();

                    jQuery('#fhLogistics_articles_articleInput .progress').slideUp();
                    jQuery('#fhLogistics_articles_articleInput .interaction').slideDown();
                    jQuery('#fhLogistics_scan_loading').slideUp();
                    jQuery('#fhLogistics_article_input_code').val('').focus();

                } else {
                    alert("Si è verificato un errore (" + result.result + "): " + result.description);
                    jQuery('#fhLogistics_articles_articleInput .progress').slideUp();
                    jQuery('#fhLogistics_articles_articleInput .interaction').slideDown();
                    jQuery('#fhLogistics_scan_loading').slideUp();
                }
            } else {
                alert("Si è verificato un errore (" + result.result + "): " + result.description);
                jQuery('#fhLogistics_articles_articleInput .progress').slideUp();
                jQuery('#fhLogistics_articles_articleInput .interaction').slideDown();
                jQuery('#fhLogistics_scan_loading').slideUp();
            }
        }
    );

}

function fhLogistics_refreshSessionArticlesTable() {

    jQuery('#fhLogistics_articles_sessionArticles_table').html('');

    var articlesCount = 0;
    for (var i = 0; i < fhLogistics_data.udmArticlesSession.length; i++) {
        var articleSession = fhLogistics_data.udmArticlesSession[i];

        if (articleSession == undefined) {
            continue;
        }

        var rowHtml =
            '<tr>' +
                '<td id="articleSession_code" >' + articleSession.article_code  + '</td>' +
                '<td>' + articleSession.name  + '</td>' +
				'<td style="text-align: center; width: 75px;"><input type="text" style="width: 50px; text-align: center" class="articleSession_quantity" id="articleSession_quantity_' + articleSession.article_id + '" value="' + articleSession.quantity + '" /></td>' +
                '<td style="text-align: center;">' +
                    '<a href="#" onclick="fhLogistics_sessionArticleEdit(' + articleSession.article_id + ', 1); return false;"><i class="icon-plus"></i></a> ' +
                    '<a href="#" onclick="fhLogistics_sessionArticleEdit(' + articleSession.article_id + ', -1); return false;"><i class="icon-minus"></i></a> ' +
                '</td>' +
            '</tr>';

        jQuery('#fhLogistics_articles_sessionArticles_table').append(rowHtml);

        articlesCount++;
    }

    console.log(articlesCount);

    if (articlesCount == 0) {
        jQuery('#fhLogistics_articles_sessionArticles_table').append('<tr><td colspan="3">Nessun articolo presente</td></tr>');
    }

}

function fhLogistics_sessionArticleEdit(articleId, operation) {

    fhLogistics_data.udmArticlesSession[articleId].quantity += operation;
    if (fhLogistics_data.udmArticlesSession[articleId].quantity < 0) {
        fhLogistics_data.udmArticlesSession[articleId].quantity = 0;
    }


    fhLogistics_refreshSessionArticlesTable();

}

function fhLogistics_saveSession() {
	if(jQuery('#articleSession_code').html()== undefined) {
		if (jQuery('#article_stored_code').html()== undefined) {
			alert("UDM nuova, non si puo' salvare senza immettere un articolo");
		} else if (confirm("Sei sicura che vuoi salvare")) {
			fhLogistics_save()
		}
	} else {
		if(jQuery('.articleSession_quantity').val()== 0) {
		alert("Occorre indicare una quantita");
		} else {
		fhLogistics_save()
		}	
	}		
}
			
function fhLogistics_save() {
	jQuery('#fhLogistics_articles').slideUp();
	jQuery('#fhLogistics_saving').slideDown();
		
	var udmCode = fhLogistics_data.udmCode;
	var ubicazione = jQuery('#fhlogistics_ubicazione_input_code_'+ fhLogistics_data.udmCode ).val();
	if (ubicazione == undefined) {
		ubicazione = '';
	} 		
		
	var type = jQuery('#fhLogistics_articles_udmCode').html().substring(12,13);
			
	if (type === '1') {
		var stockId = 1;
	} else {
		var stockId = 3;
	}
		
	var articlesData = new Array();
	for (var i = 0; i < fhLogistics_data.udmArticlesSession.length; i++) {
		if (fhLogistics_data.udmArticlesSession[i] != undefined) {
			var articleId = fhLogistics_data.udmArticlesSession[i].article_id;
			fhLogistics_data.udmArticlesSession[i].quantity = parseInt(jQuery('#articleSession_quantity_' + articleId).val());
			articlesData.push(fhLogistics_data.udmArticlesSession[i]);
		}
	}

	jQuery.post(
		'index.php?controller=logistics&task=jsonSaveSession&type=json',
		{
			"udmCode": fhLogistics_data.udmCode,
			"articlesData": JSON.stringify(articlesData),
			"stockId": stockId , 
			"ubicazione": ubicazione
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			if (result != false) {
				if (result.result >= 0) {
					alert('Operazione completata');
					window.location.reload();
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
					jQuery('#fhLogistics_articles').slideDown();
					jQuery('#fhLogistics_saving').slideUp();
				}
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhLogistics_articles').slideDown();
				jQuery('#fhLogistics_saving').slideUp();
			}
		}
	);
}
	




function fhLogistics_printUdm(udmCode) {

    jQuery.post(
        'index.php?controller=logistics&task=jsonPrintUdm&type=json',
        {
            "udmCode": udmCode
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


function fhLogistics_openLogistics() {
	
	window.location = 'index.php?controller=logistics';
	
}
