<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="popupAddLdv_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">
	<div class="search">		
		<div style="margin-bottom: 5px;">
			<button class="btn btn-primary pull-right"  onclick="popupAddLdv_add(); return false;"><i class="icon-white icon-plus"></i>Aggiungi</button>
		</div>		
		<div class="results" style="margin-top: 5px;">		
			<table class="table table-bordered table-condensed" width="100%" >
				<tr class="form-inline">
					<td style="text-align: right;">
						<span class="label">
							Codice
						</span>
					</td>
					<td>
						<input type="text" id="popupAddLdv_editLdvCode" />
					</td>						
					<td style="text-align: right;">
						<span class="label">
							Data
						</span>
					</td>	
					<td>
						<input type="text" id="popupAddLdv_editLdvDate" />
					</td>
				</tr>
				<tr class="form-inline">
					<td style="text-align: right;">
						<span class="label">
							Mittente
						</span>
					</td>	
					<td>
						<input type="text" id="popupAddLdv_editLdvSender" />
					</td>
					<td style="text-align: right;">
						<span class="label">
							Note
						</span>
					</td>	
					<td>
						<input type="text" id="popupAddLdv_editLdvNotes" list="notes"/>
						<datalist id="notes">
							<option value="">
							<option value="LDV ASSENTE">
						</datalist>
					</td>
				</tr>
				<tr class="form-inline">
					<td style="text-align: right;">
						<span class="label">
							Colli
						</span>
					</td>	
					<td>
						<input type="text" id="popupAddLdv_editLdvCarton" />
					</td>
					<td style="text-align: right;">
						<span class="label">
							Bancali
						</span>
					</td>
					<td>
						<input type="text" id="popupAddLdv_editLdvPallet" />
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
	

<script>

	jQuery(document).ready(function () {
		
		<?php if ($this->ldvId > 0) { ?>
    	popupAddLdv_editLdv(<?php echo $this->ldvId; ?>);
    	<?php } else { ?>
    	popupAddLdv_newLdv();
    	<?php } ?>
		
    		
		jQuery('#popupAddLdv_editLdvDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
	});
	
	    
</script>