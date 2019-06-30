<div id="fhAggregation" class="container">
	<div class="headerbar">
		<h2>Aggregazione cartoni</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row" style="margin-bottom: 10px;">
			<button class="btn btn-large btn-primary pull-right" id="fhAggregation_aggregateBtn" onclick="fhAggregation_aggregate(); return false" style="display: none;">Aggrega selezionati</button>
		</div>
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhAggregation_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="50px"></th>
						<th width="75px;" style="text-align: center">Codice</th>
						<th>Nome</th>
						<th width="75px;" style="text-align: right">Conf. OK</th>
						<th width="75px;" style="text-align: right">Conf./Cart.</th>
						<th width="75px;" style="text-align: right">Cart. da aggregare</th>
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
	
		fhAggregation_search();
		
	});
	
</script>