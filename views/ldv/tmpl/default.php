<div id="fhLdv" class="container">
	<div class="headerbar">
		<h2>Lettera di vettura</h2>
	</div>
	<div class="toolbar">
		<button class="btn btn-primary" onclick="fhLdv_addDdt(); return false;">Ddt</button>
		<button class="btn btn-success pull-right" onclick="fhLdv_save(); return false;">Salva</button>
	</div>
	
	<div class="main">
	
		<div class="progress progress-striped active" style="display: none;">
		    <div class="bar" style="width: 100%;"></div>
		</div>		
		
		<div class="row" style="margin-bottom: 10px;">
			<div class="span6">
				Lettera di vettura <span id="fhLdv_code"></span> del <span id="fhLdv_date"></span>
			</div>
			<div class="span6" style="text-align: right">
				<span id="fhLdv_labelArchived">LAVORARE</span>
				<a href="#" onclick="fhLdv_archivedToggle(); return false;" id="fhLdv_btnArchivedToggle"></a>
			</div>
		</div>
		
		<table id="fhLdv_details" class="table table-condensed table-striped table-bordered" width="100%">
			<thead>
				<tr>
					<th style="text-align: center">Ddt</th>
					<th style="text-align: left">data</th>
					<th width="100px;" style="text-align: center"></th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
			</tfoot>
		</table>
		
	</div>
</div>

<script>

	jQuery(document).ready(function() {
		
		fhLdv_load(<?php echo $this->ldv->ldv_id; ?>);
		
	});
	
</script>