<style>
	#popupVector_tab_info table td {
		vertical-align: middle;
	}
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupVector_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<div id="popupVector_tabs">
		<ul class="nav nav-tabs">
    		<li><a href="#popupVector_tab_info" data-toggle="tab">Info</a></li>
    	</ul>
    	<div id="popupVector_tabs_content" class="tab-content">
			<div id="popupVector_tab_info" class="tab-pane">
				
				<table class="table table-bordered table-condensed" width="100%">
					<tr class="form-inline">
						<td style="text-align: right;">
							<span class="label">Nome</span>
						</td>
						<td>
							<input type="text" id="popupVector_editName" />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	<button class="btn btn-success" onclick="popupVector_save(); return false;">Salva</button>
</div>

<script>

	jQuery(document).ready(function () {
		<?php if ($this->vectorId > 0) { ?>
    	popupVector_editVector(<?php echo $this->vectorId; ?>);
    	<?php } else { ?>
    	popupVector_newVector();
    	<?php } ?>
    	jQuery('#popupVector_tabs li a:first').tab("show");
	});
	    
</script>