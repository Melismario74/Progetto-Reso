<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupUdmList_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">
	<div class="search">
		<div class="filters row">
			<div class="span1_4">
				
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="popupUdmList_filterArticleCode" name="popupUdmList_filterArticleCode" placeholder="Codice articolo" value="" data-return-action="popupUdmList_search_btn" />
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" id="popupUdmList_search_btn" onclick="popupUdmList_search(); return false;"><i class="icon-white icon-search" ></i> Cerca</button>
			</div>
		</div>

		<div class="results">
			
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			
			<?php if (DMAcl::checkPrivilege('FH_ARTICLE_MANAGE')) { ?>
			<div style="margin-bottom: 10px;">
				<table>
					<tr class="form-inline">
						<td>
							Forza:
						</td>
						<td>
							<input type="text" id="popupUdmList_editUdmCode" placeholder="UDM" style="width: 150px;"></input>
						</td>
						<td>
							<input type="text" id="popupUdmList_editArticleCode" placeholder="Codice articolo" style="width: 150px;"></input>
						</td>
						<td>
							<input type="text" id="popupUdmList_editQuantity" placeholder="Quantità" style="width: 150px;"></input>
						</td>
						<td>
							<button class="btn btn-primary" onclick="popupUdmList_addArticleToUdm(); return false;">Aggiungi</button>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
			
			<table id="popupUdmLists_table" class="table table-bordered table-condensed table-striped" width="100%">
				<thead>
					<tr>
						<th style="text-align: center;">UDM</th>
						<th style="text-align: center;">Articolo</th>
						<th>Descrizione</th>
						<th style="text-align: center;">Quantità</th>
						<?php if($this->canSelect) { ?>
						<th></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

	popupUdmList_canSelect = <?php echo $this->canSelect; ?>;
	popupUdmList_canPrint = <?php echo $this->canPrint; ?>;

	jQuery(document).ready(function () {
		
		popupUdmList_refreshUdms();		
		
		
	});
	    
</script>