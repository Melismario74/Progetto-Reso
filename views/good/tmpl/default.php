<div id="fhGood" class="container">
	<div class="headerbar">
		<h2>Smistamento articoli buoni</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="window.location.reload(); return false;">Nuova sessione</button>
	</div>
	
	<div class="main">
	
		<div id="fhGood_process">
		
			<div id="fhGood_scan">
			
				<table class="table table-bordered">
					<tr>
						<td width="30%" style="vertical-align: middle; font-weight: bold;">
							Lettura codice
						</td>
						<td class="form-inline" style="width: 200px;">
							<div class="input-append">
					    		<input class="span2" id="fhGood_scan_code" type="text" data-return-action="fhGood_barcodeSearch_btn" />
    							<button id="fhGood_barcodeSearch_btn" class="btn btn-success" type="button" onclick="fhGood_barcodeSearch(); return false;">Invia</button>    				
    						</div>
    						<button class="btn btn-primary" onclick="fhGood_articleSearch(); return false;"><i class="icon-white icon-search"></i> Cerca articolo</button>
						</td>
					</tr>
					<tr id="fhGood_scan_loading" style="display: none">
						<td colspan="2">
							<div class="progress progress-striped active">
								<div class="bar" style="width: 100%;"></div>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
			
			<div id="fhGood_article" style="display: none;">
				<h4>Articolo</h4>
				
				<div id="fhGood_article_loading" class="progress progress-striped active" style="display: none">
					<div class="bar" style="width: 100%;"></div>
				</div>
				
				<table id="fhGood_article_data" class="table table-bordered table-condensed">
					<tr>
						<td style="text-align: right; width: 200px;">
							<span class="label">Codice:</span>
						</td>
						<td class="value" id="fhGood_article_articleCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Descrizione:</span>
						</td>
						<td class="value" id="fhGood_article_name" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">EAN:</span>
						</td>
						<td class="value" id="fhGood_article_eanCode" colspan="3">
						</td>
					</tr>
				
					<tr>
						<td style="text-align: right">
							<span class="label">Quantità:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhGood_article_quantity" name="fhGood_article_quantity" />
						</td>
					</tr>
				</table>
    			   		
    			<div id="fhGood_actions" class="row">
    				<div class="span4">
						<button class="btn btn-success btn-large btn-block" onclick="fhGood_process(1); return false;">BUONO</button>
    				</div>
    							
    			</div>
    		
			</div>
			
		</div>
		
		
		
	</div>
	
	<div id="fhGood_loading" style="display: none;">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>	
</div>

<script>

	jQuery(document).ready(function() {
		
		fhGood_finishProcess()		
		
	});
	
</script>