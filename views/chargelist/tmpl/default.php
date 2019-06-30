<div id="fhChargelist" class="container">
	<div class="headerbar">
		<h2>Lista di carico</h2>
	</div>
	<div class="toolbar">
		<a href="<?php echo DMUrl::getCurrentBaseUrl(); ?>index.php?controller=chargelist&task=exportChargelist&chargelistId=<?php echo $this->chargelist->chargelist_id; ?>" class="btn btn-primary">Scarica TXT</a>
	</div>
	
	<div class="main">
	
		<div class="progress progress-striped active" style="display: none;">
		    <div class="bar" style="width: 100%;"></div>
		</div>		
		
		<div class="row" style="margin-bottom: 10px;">
			<div class="span6">
				Lista di carico <span id="fhChargelist_code"></span> del <span id="fhChargelist_date"></span>
			</div>
			<div class="span6" style="text-align: right">
				<span id="fhChargelist_labelArchived">ARCHIVIATA</span>
				<a href="#" onclick="fhChargelist_archivedToggle(); return false;" id="fhChargelist_btnArchivedToggle"></a>
			</div>
		</div>
		
		<table class="table table-condensed table-striped table-bordered" width="100%">
			<thead>
				<tr>
					<th style="text-align: center">Codice Articolo</th>
					<th style="text-align: left">Descrizione</th>
					<th style="text-align: center">Conf.</th>
					<th style="text-align: center">Buone</th>
					<th style="text-align: center">Stock</th>
					<th style="text-align: center">Scarto</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<td colspan="8" style="text-align: center;"></td>
				</tr>
			</tfoot>
		</table>
		
	</div>
</div>

<script>

	jQuery(document).ready(function() {
	
		jQuery('#fhChargelist_form').validate();
		
		fhChargelist_load(<?php echo $this->chargelist->chargelist_id; ?>);
		
	});
	
</script>