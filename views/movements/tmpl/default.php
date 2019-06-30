<div id="fhMovements" class="container">
	<div class="headerbar">
		<h2>Movimenti</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterMovementDateFrom" name="fhMovements_filterMovementDateFrom" placeholder="Data da" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterMovementDateTo" name="fhMovements_filterMovementDateTo" placeholder="Data a" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterBatchInCode" name="fhMovements_filterBatchInCode" placeholder="Lotto ingresso" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterBatchOutCode" name="fhMovements_filterBatchOutCode" placeholder="Lotto uscita" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterArticleCode" name="fhMovements_filterArticleCode" placeholder="Cod. Articolo" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhMovements_filterEanCode" name="fhMovements_filterEanCode" placeholder="Cod. EAN" value="" data-return-action="fhMovements_search_btn" />
			</div>
			<div class="span2">
				<select id="fhMovements_filterMovementType" name="fhMovements_filterMovementType" class="fhMovements_filterMovementType span2" data-return-action="fhMovements_search_btn">
		        	<option value="">Tipo di movimento</option>
		        	<option value="AGGREGATE"><?php echo DMLang::_("AGGREGATE"); ?></option>
		        	<option value="PROCESS"><?php echo DMLang::_("PROCESS"); ?></option>
		        	<option value="MANUAL"><?php echo DMLang::_("MANUAL"); ?></option>
		        	<option value="INVOICE"><?php echo DMLang::_("INVOICE"); ?></option>
		     	</select>
			</div>
			<div class="span2">
				<select id="fhMovements_filterUser" name="fhMovements_filterUser" class="fhMovements_filterUser span2" data-return-action="fhMovements_search_btn">
		        	<option value="">Utente</option>
		     	</select>
			</div>
			<div class="span2">
				<select id="fhMovements_filterStock" name="fhMovements_filterStock" class="fhMovements_filterStock span2" data-return-action="fhMovements_search_btn">
		        	<option value="">Magazzino</option>
		     	</select>
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" onclick="fhMovements_search(); return false;" id="fhMovements_search_btn"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
			<div class="span3">
				<button class="btn btn-warning" onclick="fhMovements_export(); return false;" id="fhMovements_export_btn">TXT movimenti liste di carico</button>
			</div>
			<div class="span2">
				<button class="btn btn-warning" onclick="fhMovements_exportCSV(); return false;" id="fhMovements_exportCSV_btn">Esporta CSV</button>
			</div>
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhMovements_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th width="75px;" style="text-align: center">Data</th>
						<th width="75px;" style="text-align: center">Tipo</th>
						<th width="75px;">Cod.Articolo</th>
						<th>Descrizione</th>
						<th width="75px;" style="text-align: center">Lotto in.</th>
						<th width="75px;" style="text-align: center">Lotto usc.</th>
						<th width="75px;" style="text-align: center">Utente</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8" style="text-align: center;"></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
	</div>
</div>

<script>

	jQuery(document).ready(function() {
	
		jQuery('#fhMovements_filterMovementDateFrom').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('#fhMovements_filterMovementDateTo').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('.filters').cascadingDropdown({
    	    selectBoxes: [
    	        {
    	            selector: '.fhMovements_filterUser',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=user&type=json&task=jsonGetUsers',
    	            textKey: 'name',
    	            valueKey: 'user_id',
    	            paramName: 'fhMovements_filterUser',
    	            defaultKey: '',
    	            dataElement: 'users'
    	        }
    	    ]
    	});
		
		jQuery('.filters').cascadingDropdown({
    	    selectBoxes: [
    	        {
    	            selector: '.fhMovements_filterStock',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=stock&type=json&task=jsonGetStocks',
    	            textKey: 'name',
    	            valueKey: 'stock_id',
    	            paramName: 'fhMovements_filterStock',
    	            defaultKey: '',
    	            dataElement: 'stocks'
    	        }
    	    ]
    	});
    	
    	fhMovements_search();
		
	});
	
</script>