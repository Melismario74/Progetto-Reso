<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupOrderImport_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<div id="popupOrderImport_loading" style="display: none;">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>
	<table id="popupOrderImport_orders" width="100%" class="table table-condensed table-bordered table-striped">
		<thead>
			<tr>
				<td>Nome file</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

	jQuery(document).ready(function () {
	
		popupOrderImport_refresh();
    	
	});
	    
</script>