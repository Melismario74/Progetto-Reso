<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="popupLdv_label"><?php echo $this->title; ?></h3>
	<div>
		<span id="popupLdv_code"></span> del <span id="popupLdv_date"></span>
	</div>
</div>
<div class="modal-body">
	<div class="search">
		<div style="margin-bottom: 5px;">
			<button class="btn btn-primary" onclick="popupLdv_addDdt(); return false;">Inserisci ddt</button>
			<button class="btn btn-success pull-right" onclick="popupLdv_save(); return false;">Salva</button>
		</div>
		<div class="results">	
			<table  id="popupLdv_table_ddt" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th style="text-align: center">
							Ddt
						</th>
						<th style="text-align: center">
							Data
						</th>
						<th style="text-align: center">
							Carico
						</th>
						<th style="text-align: center">
							Note
						</th>						
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>


<script>

	jQuery(document).ready(function () {
		
    	popupLdv_load(<?php echo $this->ldvId; ?>);
    
	});
	    
</script>