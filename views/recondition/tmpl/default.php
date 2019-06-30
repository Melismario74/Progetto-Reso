<div id="fhRecondition" class="container">
	<div class="headerbar">
		<h2>Ricondizionamento</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="window.location.reload(); return false;">Nuova sessione</button>
	</div>
	
	<div class="main">
	
		<div id="fhRecondition_chargelist">
			
			<table class="table table-bordered">
				<tr>
					<td width="30%" style="vertical-align: middle; font-weight: bold;">
						Lista di carico
					</td>
					<td>
						<div id="fhRecondition_chargelist_choice">
							<button class="btn" onclick="fhRecondition_chargelistChoice(); return false;">Inizia sessione di lavoro</button>
						</div>
						<div id="fhRecondition_chargelist_loading" class="progress progress-striped active" style="display: none">
							<div class="bar" style="width: 100%;"></div>
						</div>
						<div id="fhRecondition_chargelist_loaded" style="display: none;">
							<span id="fhRecondition_chargelist_code"></span> del <span id="fhRecondition_chargelist_date"></span>
						</div>
					</td>
				</tr>
			</table>
			
		</div>
		
		<div id="fhRecondition_process" style="display: none";>
		
			<div id="fhRecondition_scan">
			
				<table class="table table-bordered">
					<tr>
						<td width="30%" style="vertical-align: middle; font-weight: bold;">
							Lettura codice
						</td>
						<td class="form-inline" style="width: 200px;">
							<div class="input-append">
					    		<input class="span2" id="fhRecondition_scan_code" type="text" data-return-action="fhRecondition_barcodeSearch_btn" />
    							<button id="fhRecondition_barcodeSearch_btn" class="btn btn-success" type="button" onclick="fhRecondition_barcodeSearch(); return false;">Invia</button>    				
    						</div>
    						<button class="btn btn-primary" onclick="fhRecondition_articleSearch(); return false;"><i class="icon-white icon-search"></i> Cerca articolo</button>
						</td>
					</tr>
					<tr id="fhRecondition_scan_loading" style="display: none">
						<td colspan="2">
							<div class="progress progress-striped active">
								<div class="bar" style="width: 100%;"></div>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
			
			<div id="fhRecondition_article" style="display: none;">
				<h4>Articolo</h4>
				
				<div id="fhRecondition_article_loading" class="progress progress-striped active" style="display: none">
					<div class="bar" style="width: 100%;"></div>
				</div>
				
				<table id="fhRecondition_article_data" class="table table-bordered table-condensed">
					<tr>
						<td rowspan="8" style="text-align: center;" width="200px;">
							<img id="fhRecondition_article_image" />
						</td>
						<td style="text-align: right; width: 200px;">
							<span class="label">Codice:</span>
						</td>
						<td class="value" id="fhRecondition_article_articleCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Descrizione:</span>
						</td>
						<td class="value" id="fhRecondition_article_name" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">EAN:</span>
						</td>
						<td class="value" id="fhRecondition_article_eanCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Conf./Cart.:</span>
						</td>
						<td class="value" id="fhRecondition_article_packageUnits" style="width: 50px;">
						</td>
						<td style="text-align: right">
							<span class="label">Codice imballo</span>
						</td>
						<td class="value" id="fhRecondition_article_packageCode">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Lotto:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhRecondition_article_batchInCode" name="fhRecondition_article_batchInCode" onchange="fhRecondition_article_batchInCodeChanged();"/>
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Quantità:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhRecondition_article_quantity" name="fhRecondition_article_quantity" />
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">E' un cartone:</span>
						</td>
						<td class="value" colspan="3">
							<input type="checkbox" id="fhRecondition_article_isPackage" name="fhRecondition_article_isPackage" value="1" onclick="fhRecondition_article_togglePackage();" />
						</td>
					</tr>
					<tr>
						<td colspan="4"></td>
					</tr>
				</table>
				
				<div id="fhRecondition_article_chargelistalerts" class="alert alert-error" style="display: none;">
    				<strong>Attenzione! L'articolo non è presente nella lista di carico!</strong>
    				<input type="checkbox" value="1" id="fhRecondition_article_forceChargelistInsert"></input> Forza l'inserimento
    			</div>
				
				<div id="fhRecondition_article_qualityalerts" style="display: none;">
					<div id="fhRecondition_article_qualityalerts_error" class="alert alert-error">
						<div>
	    					<strong>Attenzione! La qualità del lotto è segnalata con il seguente messaggio:</strong>
    						<span id="fhRecondition_article_qualityalerts_error_message"></span>
    					</div>
    					<div>
	    					<input type="checkbox" value="1" id="fhRecondition_article_forceQuality"></input> Conferma ugualmente il ricondizionamento
    					</div>
					</div>
					<div id="fhRecondition_article_qualityalerts_warning" class="alert">
						<div>
	    					<strong>Attenzione! La qualità di alcuni lotti dell'articolo (<span id="fhRecondition_article_qualityalerts_warning_batchInCodes"></span>) è segnalata con il seguente messaggio:</strong>
    						<span id="fhRecondition_article_qualityalerts_warning_message"></span>
    					</div>
					</div>
    			</div>
    			   		
    			<div id="fhRecondition_actions" class="row">
    				
    				<div class="span4">
    					<button class="btn btn-success btn-large btn-block" onclick="fhRecondition_process(1); return false;">BUONO</button>
    				</div>
    				<div class="span4">
    					<button class="btn btn-warning btn-large btn-block" onclick="fhRecondition_process(3); return false;">STOCK</button>
    				</div>
    				<div class="span4">
    					<button class="btn btn-danger btn-large btn-block" onclick="fhRecondition_process(2); return false;">SCARTO</button>
    				</div>
    			
    			</div>
    		
			</div>
			
		</div>
		
		<div id="fhRecondition_progress" style="display: none;">
			<div style="text-align: center;">
				Il ricondizionamento è in corso...
			</div>
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>			
		</div>
		
	</div>
	
	<div id="fhRecondition_loading" style="display: none;">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>	
</div>

<script>

	jQuery(document).ready(function() {
		
	});
	
</script>