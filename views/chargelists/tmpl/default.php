<div id="fhChargelists" class="container">
	<div class="headerbar">
		<h2>Liste di carico</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-warning" onclick="fhChargelists_search(); return false">Aggiorna</button>
		
		<?php if (DMAcl::checkPrivilege("FH_CHARGELIST_IMPORT")) ?>
		<button class="btn btn-primary" onclick="fhChargelists_importChargelist_click(); return false">Importa da FTP</button>
		
	</div>
	
	<div class="main search">
		
		<div class="filters">
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhChargelists_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th width="100px;" style="text-align: center">Data</th>
						<th>Codice</th>
						<th width="50px" style="text-align: center">Arch.</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	</div>
</div>

<script>

	jQuery(document).ready(function() {
	
		fhChargelists_search();
		
	});
	
</script>