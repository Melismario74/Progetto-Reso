<div id="fhOrders" class="container">
	<div class="headerbar">
		<h2>Liste di Prelievo</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhOrders_filterOrderDateFrom" name="fhOrders_filterOrderDateFrom" placeholder="Data da" value="" data-return-action="fhOrders_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhOrders_filterOrderDateTo" name="fhOrders_filterOrderDateTo" placeholder="Data a" value="" data-return-action="fhOrders_search_btn" />
			</div>
			<div class="span1_5">				
				<select id="fhOrders_filterOrderArchived" name="fhOrders_filterOrderArchived" class="span1_5" data-return-action="fhOrders_search_btn">
		        	<option value="">Arch.</option>
		        	<option value="Si"><?php echo DMLang::_("Si"); ?></option>
		        	<option value="No"><?php echo DMLang::_("No"); ?></option>	
		     	</select>
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" onclick="fhOrders_search(); return false;" id="fhOrders_search_btn"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
			<div class="span2 pull-right" style="text-align: right;">
				<a class="btn" href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=order&view=order" id="fhOrders_new_btn"><i class="icon-plus"></i> Nuova Lista</a>
			</div>
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhOrders_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th width="75px;" style="text-align: center">Data</th>
						<th width="150px;" style="text-align: center">Ufficio richiedente</th>
						<th style="text-align: center">Ordini</th>
						<th width="50px" style="text-align: center">Arch.</th>
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
	
		jQuery('#fhOrders_filterOrderDateFrom').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('#fhOrders_filterOrderDateTo').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	fhOrders_search();
		
	});
	
</script>