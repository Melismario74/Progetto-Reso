<style>
	#popupDdt_tab_info table td {
		vertical-align: middle;
	}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="popupDdt_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">		
	<div>
		<button class="btn btn-primary pull-right"  onclick="popupDdt_add(); return false;"><i class="icon-white icon-plus"></i>Aggiungi</button>
	</div>
	<table class="table table-bordered table-condensed" width="100%">
		<tr class="form-inline">
			<td style="text-align: right;">
				<span class="label">
					Codice
				</span>
			</td>
			<td>
				<input type="text" id="popupDdt_editDdtCode" />
			</td>						
			<td style="text-align: right;">
				<span class="label">
					Data
				</span>
			</td>	
			<td>
				<input type="text" id="popupDdt_editDdtDate" />
			</td>
		</tr>
		<tr class="form-inline">
			<td style="text-align: right;">
				<span class="label">
					Carico
				</span>
			</td>
			<td>
				<input type="text" id="popupDdt_editCargo" list="cargoList"/>
				<datalist id="cargoList">
					<option value="merce">
					<option value="accessori">
					<option value="merce + accessori">
				</datalist>
			</td>						
			<td style="text-align: right;">
				<span class="label">
					Note
				</span>
			</td>	
			<td>
				<input type="text" id="popupDdt_editNotes" list="notesList"/>
				<datalist id="notesList">
					<option value="esente">
				</datalist>
			</td>
		</tr>
	</table>			
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
	

<script>     

	jQuery(document).ready(function () {
		<?php if ($this->ddtId > 0) { ?>
    	popupDdt_editDdt(<?php echo $this->ddtId; ?>);
    	<?php } else { ?>
    	popupDdt_newDdt();
    	<?php } ?>
		jQuery('#popupDdt_tabs li a:first').tab("show");
    		
		jQuery('#popupDdt_editDdtDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
	});
	
	    
</script>