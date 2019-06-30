<div id="fhStock" class="container">
	<div class="headerbar">
		<h2>Gestione magazzino</h2>
	</div>
	
	<div class="main search">
		
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhStock_filterArticleCode" name="fhStock_filterArticleCode" placeholder="Codice articolo" value="" data-return-action="fhStock_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhStock_filterEanCode" name="fhStock_filterEanCode" placeholder="Codice EAN" value="" data-return-action="fhStock_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhStock_filterName" name="fhStock_filterName" placeholder="Descrizione" value="" data-return-action="fhStock_search_btn" />
			</div>	
			<div class="span1_5">
				<button class="btn btn-primary" onclick="fhStock_search(); return false;" id="fhStock_search_btn"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
			<div class="span2">
				<button class="btn btn-warning" onclick="fhStock_export(); return false;" id="fhStock_export_btn" style="display: none;">TXT</button>
			</div>
			<div class="span2" style="display: none;">
				<button class="btn btn-warning" onclick="fhStock_print(); return false;" id="fhStock_print_btn"><i class="icon-white icon-print" ></i> Stampa</button>
			</div>
			<div class="span3 pull-right" style="text-align: right;">	
				<button class="btn btn-warning" onclick="fhStock_exportCSV(); return false;" id="fhStock_exportCSV_btn">Esporta CSV</button>
				<button class="btn btn-primary" onclick="fhStock_showUdms(); return false;" id="fhStock_udms_btn">UDM</button>
			</div>
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="fhStock_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">Codice</th>
						<th>Nome</th>
						<th width="75px;" style="text-align: right">Conf. OK</th>
						<th width="75px;" style="text-align: right">Cart. OK</th>
						<th width="75px;" style="text-align: right">Stock</th>
						<th width="75px;" style="text-align: right">Scarto</th>
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
	
		fhStock_search();
		
	});
	
</script>