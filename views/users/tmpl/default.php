<div id="fhUsers" class="container">
	<div class="headerbar">
		<h2>Gestione utenti</h2>
	</div>
	
	<div class="toolbar">
		<button class="btn btn-primary" onclick="fhUsers_new(); return false">Nuovo utente</button>
	</div>
	
	<div class="main search">
	
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhUsers_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th>Nome</th>
						<th width="100px;" style="text-align: center"></th>
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
	
		fhUsers_search();
		
	});
	
</script>