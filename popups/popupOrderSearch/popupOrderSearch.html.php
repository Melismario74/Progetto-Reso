<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupOrderSearch_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<div class="search">
		<div class="filters">
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="popupOrderSearch_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th width="100px;" style="text-align: center">Data</th>
						<th>Codice</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

	jQuery(document).ready(function () {
	
		popupOrderSearch_search();
    	
	});
	    
</script>