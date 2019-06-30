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
	</table>			
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	<button class="btn btn-success" onclick="popupDdt_save(); return false;">Salva</button>
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