<div id="fhInvoices" class="container">
	<div class="headerbar">
		<h2>Documenti di uscita</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhInvoices_filterInvoiceDateFrom" name="fhInvoices_filterInvoiceDateFrom" placeholder="Data da" value="" data-return-action="fhInvoices_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhInvoices_filterInvoiceDateTo" name="fhInvoices_filterInvoiceDateTo" placeholder="Data a" value="" data-return-action="fhInvoices_search_btn" />
			</div>
			<div class="span1_5">				
				<select id="fhInvoices_filterInvoiceArchived" name="fhInvoices_filterInvoiceArchived" class="span1_5" data-return-action="fhInvoices_search_btn">
		        	<option value="">Arch.</option>
		        	<option value="Si"><?php echo DMLang::_("Si"); ?></option>
		        	<option value="No"><?php echo DMLang::_("No"); ?></option>	
		     	</select>
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" onclick="fhInvoices_search(); return false;" id="fhInvoices_search_btn"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
			<div class="span2 pull-right" style="text-align: right;">
				<a class="btn" href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=invoice&view=invoice" id="fhInvoices_new_btn"><i class="icon-plus"></i> Nuovo DDT</a>
			</div>
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhInvoices_results_table" width="100%" class="table table-condensed table-striped table-bordered">
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
	
		jQuery('#fhInvoices_filterInvoiceDateFrom').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('#fhInvoices_filterInvoiceDateTo').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	fhInvoices_search();
		
	});
	
</script>