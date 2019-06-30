<div id="fhGlass" class="container">
	<div class="headerbar">
		<h2>Registrazione occhiali</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="window.location.reload(); return false;">Nuova sessione</button>
	</div>
	
	<div class="main">
	
		<div id="fhGlass_process">
		
			<div id="fhGlass_scan">
			
				<table class="table table-bordered">
					<tr>
						<td width="30%" style="vertical-align: middle; font-weight: bold;">
							Lettura codice
						</td>
						<td class="form-inline" style="width: 200px;">
							<div class="input-append">
					    		<input class="span2" id="fhGlass_scan_code" type="text" data-return-action="fhGlass_barcodeSearch_btn" />
    							<button id="fhGlass_barcodeSearch_btn" class="btn btn-success" type="button" onclick="fhGlass_barcodeSearch(); return false;">Invia</button>    				
    						</div>
    						<button class="btn btn-primary" onclick="fhGlass_articleSearch(); return false;"><i class="icon-white icon-search"></i> Cerca articolo</button>
						</td>
					</tr>
					<tr id="fhGlass_scan_loading" style="display: none">
						<td colspan="2">
							<div class="progress progress-striped active">
								<div class="bar" style="width: 100%;"></div>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
			
			<div id="fhGlass_article" style="display: none;">
				<h4>Articolo</h4>
				
				<div id="fhGlass_article_loading" class="progress progress-striped active" style="display: none">
					<div class="bar" style="width: 100%;"></div>
				</div>
				
				<table id="fhGlass_article_data" class="table table-bordered table-condensed">
					<tr>
						<td style="text-align: right; width: 200px;">
							<span class="label">Codice:</span>
						</td>
						<td class="value" id="fhGlass_article_articleCode" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">Descrizione:</span>
						</td>
						<td class="value" id="fhGlass_article_name" colspan="3">
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<span class="label">EAN:</span>
						</td>
						<td class="value" id="fhGlass_article_eanCode" colspan="3">
						</td>
					</tr>
				
					<tr>
						<td style="text-align: right">
							<span class="label">Quantità:</span>
						</td>
						<td class="value" colspan="3">
							<input type="text" id="fhGlass_article_quantity" name="fhGlass_article_quantity" />
						</td>
					</tr>
				</table>
    			   		
    			<div id="fhGlass_actions" class="row">
    				<div class="span4">
    					<button class="btn btn-danger btn-large btn-block" onclick="fhGlass_process(1); return false;">OCCHIALI</button>
    				</div>  
    							
    			</div>
    		
			</div>
			
		</div>
		
		
		
	</div>
	
	<div id="fhGlass_loading" style="display: none;">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>	
</div>

<script>

	jQuery(document).ready(function() {
		
		fhGlass_finishProcess()		
		
	});
	
</script>