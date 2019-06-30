<div id="fhBuono" class="container">
	<div class="headerbar">
		<h2>Smistamento articoli </h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="window.location.reload(); return false;">Nuova sessione</button>
	</div>
	
	<div class="main">
	
		<div id="fhBuono_chargelist">
			
			<table class="table table-bordered">
				<tr> 
					<td width="30%" style="vertical-align: middle; font-weight: bold;">
						Lista di carico
					</td>
					<td>
						<!--<div id="fhBuono_chargelist_choice">
							<button class="btn" onclick="fhBuono_chargelistChoice(); return false;">Inizia sessione di lavoro</button>
						</div>-->
						<div id="fhBuono_chargelist_loading" class="progress progress-striped active" style="display: none;">
							<div class="bar" style="width: 100%;"></div>
						</div>
						<div id="fhBuono_chargelist_loaded">
							<span id="fhBuono_chargelist_code">000000001</span> del <span id="fhBuono_chargelist_date" >01/01/2018</span>
						</div>
					</td>
				</tr>
			</table>
			
		</div>
		
		<div id="fhBuono_process">
		
			<div id="fhBuono_scan">
			
				<table class="table table-bordered">
					<tr>
						<td width="30%" style="vertical-align: middle; font-weight: bold;">
							Lettura codice
						</td>
						<td class="form-inline" style="width: 200px;">
							<div class="input-append">
					    		<input class="span2" id="fhBuono_scan_code" type="text" data-return-action="fhBuono_barcodeSearch_btn" />
    							<button id="fhBuono_barcodeSearch_btn" class="btn btn-success" type="button" onclick="fhBuono_barcodeSearch(); return false;">Invia</button>    				
    						</div>
    						<button class="btn btn-primary" onclick="fhBuono_articleSearch(); return false;"><i class="icon-white icon-search"></i> Cerca articolo</button>
						</td>
					</tr>
					<tr id="fhBuono_scan_loading" style="display: none">
						<td colspan="2">
							<div class="progress progress-striped active">
								<div class="bar" style="width: 100%;"></div>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
			
			<div id="fhBuono_article" style="display: none;">
				<h4>Articolo</h4>
				
				<div id="fhBuono_article_loading" class="progress progress-striped active" style="display: none">
					<div class="bar" style="width: 100%;"></div>
				</div>
				
				<table id="fhBuono_article_data" class="table table-bordered table-condensed">
					<tr>
						<td rowspan="6" style="text-align: center;" width="auto;">
							<img id="fhBuono_article_image" />
						</td>
						<td style="text-align: right; width: 200px;">
							<span class="label">Codice:</span>
						</td>
						<td class="value" id="fhBuono_article_articleCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Descrizione:</span>
						</td>
						<td class="value" id="fhBuono_article_name" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">EAN:</span>
						</td>
						<td class="value" id="fhBuono_article_eanCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Conf./Cart.:</span>
						</td>
						<td class="value" id="fhBuono_article_packageUnits" style="width: 50px;">
						</td>
						<td style="text-align: right">
							<span class="label">Codice imballo</span>
						</td>
						<td class="value" id="fhBuono_article_packageCode">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Lotto:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhBuono_article_batchInCode" name="fhBuono_article_batchInCode" onchange="fhBuono_article_batchInCodeChanged();"/>
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Quantità:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhBuono_article_quantity" name="fhBuono_article_quantity" />
						</td>
					</tr>
				</table>
				
				<div id="fhBuono_article_chargelistalerts" class="alert alert-error" style="display: none;">
    				<strong>Attenzione! L'articolo non è presente nella lista di carico!</strong>
    				<input type="checkbox" value="1" id="fhBuono_article_forceChargelistInsert"></input> Forza l'inserimento
    			</div>
				
				<div id="fhBuono_article_qualityalerts" style="display: none;">
					<div id="fhBuono_article_qualityalerts_error" class="alert alert-error">
						<div>
	    					<strong>Attenzione! La qualità del lotto è segnalata con il seguente messaggio:</strong>
    						<span id="fhBuono_article_qualityalerts_error_message"></span>
						</div>
						<div>
	    					<input type="checkbox" value="1" id="fhBuono_article_forceQuality"></input> Conferma ugualmente il ricondizionamento
						</div>
					</div>
					<div id="fhBuono_article_qualityalerts_warning" class="alert">
						<div>
	    					<strong>Attenzione! La qualità di alcuni lotti dell'articolo (<span id="fhBuono_article_qualityalerts_warning_batchInCodes"></span>) è segnalata con il seguente messaggio:</strong>
    						<span id="fhBuono_article_qualityalerts_warning_message"></span>
						</div>
					</div>
				</div>
    			   		
    			<div id="fhBuono_actions" class="row">
    				<div class="span4">
					<button class="btn btn-success btn-large btn-block" onclick="fhBuono_process(1); return false;">BUONO</button>
    				</div>
    				<div class="span4">
    					<button class="btn btn-warning btn-large btn-block" onclick="fhBuono_process(3); return false;">IBRIDO</button>
    				</div>
    				<div class="span4">
    					<button class="btn btn-danger btn-large btn-block" onclick="fhBuono_process(2); return false;">SCARTO</button>
    				</div>    			
    			</div>
    		
			</div>
			
		</div>
		
		<div id="fhBuono_progress" style="display: none;">
			<div style="text-align: center;">
				Il ricondizionamento è in corso...
			</div>
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>			
		</div>
		
	</div>
	
	<div id="fhBuono_loading" style="display: none;">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>	
</div>

<script>

	jQuery(document).ready(function() {
		
		fhBuono_finishProcess()		
		
	});
	
</script>