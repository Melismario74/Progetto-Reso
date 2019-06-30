<style>
	#popupUser_tab_info table td {
		vertical-align: middle;
	}
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupUser_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<div id="popupUser_tabs">
		<ul class="nav nav-tabs">
    		<li><a href="#popupUser_tab_info" data-toggle="tab">Info</a></li>
    		<li><a href="#popupUser_tab_groups" data-toggle="tab">Gruppi</a></li>
    	</ul>
    	<div id="popupUser_tabs_content" class="tab-content">
			<div id="popupUser_tab_info" class="tab-pane">
				
				<table class="table table-bordered table-condensed" width="100%">
					<tr class="form-inline">
						<td style="text-align: right;">
							<span class="label">Nome</span>
						</td>
						<td>
							<input type="text" id="popupUser_editName" />
						</td>
					</tr>
					<tr class="form-inline">
						<td style="text-align: right;">
							<span class="label">Nome utente</span>
						</td>
						<td>
							<input type="text" id="popupUser_editUsername" />
						</td>
					</tr>
					<tr class="form-inline">
						<td style="text-align: right;">
							<span class="label">Password</span>
						</td>
						<td>
							<input type="password" id="popupUser_editPassword" />
						</td>
					</tr>
				</table>
				
			</div>
			<div id="popupUser_tab_groups" class="tab-pane results">
				<table class="table table-bordered table-condensed" width="100%">
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	<button class="btn btn-success" onclick="popupUser_save(); return false;">Salva</button>
</div>

<script>

	jQuery(document).ready(function () {
		<?php if ($this->userId > 0) { ?>
    	popupUser_editUser(<?php echo $this->userId; ?>);
    	<?php } else { ?>
    	popupUser_newUser();
    	<?php } ?>
    	jQuery('#popupUser_tabs li a:first').tab("show");
	});
	    
</script>