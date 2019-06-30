<div id="fhArrivals" class="container">
	<div class="headerbar">
		<h2>Documenti in entrata</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhArrivals_filterArrivalDateFrom" name="fhArrivals_filterArrivalDateFrom" placeholder="Data da" value="" data-return-action="fhArrivals_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhArrivals_filterArrivalDateTo" name="fhArrivals_filterArrivalDateTo" placeholder="Data a" value="" data-return-action="fhArrivals_search_btn" />
			</div>
			<div class="span1_5">				
				<select id="fhArrivals_filterArrivalArchived" name="fhArrivals_filterArrivalArchived" class="span1_5" data-return-action="fhArrivals_search_btn">
		        	<option value="">Lavorabile</option>
		        	<option value="Si"><?php echo DMLang::_("Si"); ?></option>
		        	<option value="No"><?php echo DMLang::_("No"); ?></option>	
		     	</select>
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" onclick="fhArrivals_search(); return false;" id="fhArrivals_search_btn"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
			<div class="span2 center" style="text-align: right;">
				<button class="btn btn-warning" onclick="fhStock_exportCSV(); return false;" id="fhStock_exportCSV_btn">Esporta CSV</button>
			</div>
			<div class="span3 pull-right" style="text-align: right;">	
				<a class="btn" href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=arrival&view=arrival" id="fhArrivals_new_btn"><i class="icon-plus"></i> Nuovo ingresso</a>
			</div>
		</div>
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhArrivals_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="20px;" style="text-align: center">#</th>
						<th width="50px;" style="text-align: center">Data</th>
						<th width="75px;" style="text-align: center">Vettore</th>
						<th width="50px;" style="text-align: center">Causale</th>
						<th width="50px;" style="text-align: center">Ldv</th>
						<th width="25px;" style="text-align: center">Ddt</th>
						<th width="75px;"style="text-align: center">Note</th>
						<th width="20px" style="text-align: center">Lav</th>
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
	
		jQuery('#fhArrivals_filterArrivalDateFrom').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('#fhArrivals_filterArrivalDateTo').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	fhArrivals_search();
		
	});
	
</script>