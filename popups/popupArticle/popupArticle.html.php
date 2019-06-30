<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupArticle_label"></h3>
</div>
<div class="modal-body">

	<div id="popupArticle_tabs">
		<ul class="nav nav-tabs">
    		<li><a href="#popupArticle_tab_info" data-toggle="tab">Info</a></li>
    		<li><a href="#popupArticle_tab_movements" data-toggle="tab">Movimenti</a></li>
    		<li><a href="#popupArticle_tab_quality" data-toggle="tab">Qualità</a></li>
    		<li><a href="#popupArticle_tab_batchIn" data-toggle="tab">Lotti ingresso</a></li>
    		<li><a href="#popupArticle_tab_batchOut" data-toggle="tab">Lotti uscita</a></li>
    	</ul>
    	<div id="popupArticle_tabs_content" class="tab-content">
			<div id="popupArticle_tab_info" class="tab-pane">
				<table width="100%">
					<tr>
						<td id="popupArticle_articleImage" style="text-align: center">
							<div>
								<img width="100" />
							</div>
							<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
							<div style="margin-top: 10px;">
								<button class="btn btn-danger btn-mini" onclick="popupArticle_deleteImage(); return false;"><i class="icon-white icon-remove"></i> Elimina immagine</button>
							</div>
							<?php } ?>
						</td>
						<td style="vertical-align: top">
							<table class="table table-condensed table-bordered">
								<tr>
									<td>
										<span class="label">
											Codice
										</span>
									</td>
									<td colspan="2">
										<span id="popupArticle_articleCode" class="value">
											
										</span>
									</td>
								</tr>
								<tr>
									<td>
										<span class="label">
											EAN
										</span>
									</td>
									<td colspan="2">
										<span id="popupArticle_eanCode" class="value">
											
										</span>
									</td>
								</tr>
							</table>
							<table id="popupArticle_articleUdms" width="100%" class="table table-bordered table-condensed">
							    <thead>
							    	<tr>
							    		<th width="50%">UDM</th>
							    		<th width="30%" style="text-align: center;">Conf.</th>
							    		<th width="20%" style="text-align: center;"></th>
							    	</tr>
							    </thead>
							    <tbody></tbody>
							</table>
						</td>
						<td>
							<table id="popupArticle_articleStocks" width="100%" class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th></th>
										<th style="text-align: center;">Conf.</th>
										<th style="text-align: center;">Cart.</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id="popupArticle_tab_movements" class="tab-pane results">
				<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
				<div style="margin-bottom: 5px;">
					<button class="btn btn-primary btn-mini" onclick="popupArticle_addMovement(); return false;">Inserisci movimento</button>
				</div>
				<?php } ?>
				<table id="popupArticle_articleMovements" class="table table-bordered table-condensed table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center">
								#
							</th>
							<th style="text-align: center">
								Data
							</th>
							<th style="text-align: center">
								Tipo
							</th>
							<th style="text-align: center">
								Magaz.
							</th>
							<th style="text-align: right">
								Conf.
							</th>
							<th style="text-align: right">
								Cart.
							</th>
							<th style="text-align: center">
								Utente
							</th>
							<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE'))  ?>
							<th id="popupArticle_articleMovements_actions">
							</th>
							
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: center" colspan="8"></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div id="popupArticle_tab_quality" class="tab-pane">
				<table class="table table-bordered table-condensed" width="100%">
					<tr>
						<td><span class="label">Comunicazioni qualità</span></td>
						<td><span class="label">Lotti interessati (separati da virgola, senza spazi)</span></td>
					</tr>
					<tr>
						<td>
							<textarea id="popupArticle_editQualityMessage" style="width: 95%"></textarea>
						</td>
						<td>
							<textarea id="popupArticle_editQualityBatchInCodes" style="width: 95%"></textarea>
						</td>
					</tr>
				</table>
				<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
				<div style="margin-top: -15px;">
					<button class="btn btn-success btn-mini pull-right" onclick="popupArticle_saveQuality(); return false;">Salva comunicazioni qualità</button>
				</div>
				<?php } ?>
			</div>
			<div id="popupArticle_tab_batchIn" class="tab-pane">
				<table id="popupArticle_articleBatchIns" class="table table-bordered table-condensed table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center">
								Lotto ingresso
							</th>
							<th style="text-align: center">
								Conf. buone non aggregate
							</th>
							<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
							<th></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: center" colspan="8"></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div id="popupArticle_tab_batchOut" class="tab-pane">
				<table id="popupArticle_articleBatchOuts" class="table table-bordered table-condensed table-striped" width="100%">
					<thead>
						<tr>
							<th style="text-align: center">
								#
							</th>
							<th style="text-align: center">
								Lotto ingresso
							</th>
							<th style="text-align: center">
								Quantità
							</th>
							<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
							<th></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: center" colspan="8"></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

	jQuery(document).ready(function () {
		popupArticle_loadArticle(<?php echo $this->articleId; ?>);
    	jQuery('#popupArticle_tabs li a:first').tab("show");
    	
    	<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
    	popupArticle_aclArticleManage = true;
    	<?php } else { ?>
    	popupArticle_aclArticleManage = false;
    	<?php } ?>
	});
	    
</script>