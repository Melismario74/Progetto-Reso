<div id="fhLogistics" class="container">
	<div class="headerbar">
		<h2>Logistica</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="window.location.reload(); return false;">Nuova sessione</button>
	</div>
	
	<div class="main" style="margin-top: 20px;">
		<div id="fhLogistics_process">
		
			<div id="fhLogistics_scan">
							
				<table class="table table-bordered">
					<tr>
						<td width="30%" style="vertical-align: middle; font-weight: bold;">
							Lettura codice
						</td>
						<td class="form-inline" style="width: 200px;">
							<div class="input-append">
					    		<input class="span2" id="fhLogistics_scan_code" type="text" data-return-action="fhLogistics_barcodeSearch_btn" autofocus />
    							<button id="fhLogistics_barcodeSearch_btn" class="btn btn-success" type="button" onclick="fhLogistics_barcodeSearch(); return false;">Invia</button>    				
    						</div>
    						<button class="btn btn-primary" onclick="fhLogistics_articleSearch(); return false;"><i class="icon-white icon-search"></i> Cerca articolo</button>
						</td>
					</tr>
					<tr id="fhLogistics_scan_loading" style="display: none">
						<td colspan="2">
							<div class="progress progress-striped active">
								<div class="bar" style="width: 100%;"></div>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
			
			<div id="fhLogistics_article" style="display: none" >
				<h4>Articolo</h4>
				<div id="fhLogistics_article_loading" class="progress progress-striped active" style="display: none">
					<div class="bar" style="width: 100%;"></div>
				</div>
				<table id="fhLogistics_article_data" class="table table-bordered table-condensed table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center;">UDM</th>
							<th style="text-align: center;">Articolo</th>
							<th>Descrizione</th>
							<th style="text-align: center;">Quantità</th>
							<th style="text-align: center;">Ubicazione</th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div id="fhLogistics_stocks" style="display: none" >
				<h4>Magazzino</h4>
				<table id="fhLogistics_articleStocks" width="auto" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th></th>
							<th style="text-align: center;">Conf.</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>  		
			</div>	
		</div>
		<div id="fhLogistics_udm">			
			<table width="100%" class="table table-bordered">
				<tr>
					<td width="30%" style="vertical-align: middle; font-weight: bold;">
						Crea nuova UDM
					</td>
					<td class="form-inline">
						<button id="fhLogistics_udm_new_btn" class="btn btn-success" type="button" onclick="fhLogistics_udmNew(); return false;">UDM BUONO</button>
						<button id="fhLogistics_udm_new_btn" class="btn btn-warning" type="button" onclick="fhLogistics_ibdNew(); return false;">UDM IBRIDO</button>
					</td>
				</tr>
			</table>
		</div>
		<div id="fhLogistics_articles" style="display: none">
			<h2>2. Inserisci articoli</h2>
			<div id="fhLogistics_articles_udmCode">

			</div>
			<table width="100%" class="table table-bordered" id="fhLogistics_articles_articleInput">
				<tr>
					<td style="width: 30%">
						Inserisci codice
					</td>
					<td class="form-inline">
						<div class="interaction">
							<div id="fhLogistics_article_input" class="input-append">
								<input class="span2" id="fhLogistics_article_input_code" type="text" data-return-action="fhLogistics_article_input_btn" />
								<button id="fhLogistics_article_input_btn" class="btn btn-primary" type="button" onclick="fhLogistics_articleInputCode(); return false;">Invia</button>
							</div>
						</div>
					</td>
				</tr>
				<tr id="fhBuono_scan_loading" style="display: none">
					<td colspan="2">
						<div class="progress progress-striped active">
						<div class="bar" style="width: 100%;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td>

					</td>
					<td id="fhLogistics_articles_lastArticle">

					</td>
				</tr>
			</table>
			<div id="fhLogistics_articles_sessionArticles">
				<table id="fhLogistics_button_sessionArticles_table" width="100%" class="table" style="margin-bottom: 0px;">
					<tr class="first">
						<td style="width: 30%; font-weight: bold; vertical-align: middle;">
							Articoli caricati in questa sessione
						</td>
						<td>
							<button type="button" class="btn btn-success" onclick="fhLogistics_saveSession(); return false;">Registra sessione e chiudi</button>
						</td>
					</tr>
				</table>
				<table id="fhLogistics_articles_sessionArticles_table" width="100%" class="table table-bordered">
				</table>
			</div>
			<div id="fhLogistics_articles_storedArticles">
				<table width="100%" class="table" style="margin-bottom: 0px;">
					<tr>
						<td style="width: 30%; font-weight: bold; vertical-align: middle;">
							Articoli già presenti sull'UDM
						</td>
					</tr>
				</table>
				<table id="fhLogistics_articles_storedArticles_table" width="100%" class="table table-bordered">
				</table>
			</div>
		</div>

		<div id="fhLogistics_saving" style="display: none">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
		</div>

	</div>
</div>

<script>

	
	jQuery(document).ready(function() {
		
		
	});
	
</script>