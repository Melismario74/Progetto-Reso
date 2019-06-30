<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupUdmListOrder_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">
	<div class="search">
		<div class="filters row">
			<div class="span1_4">
				
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="popupUdmListOrder_filterArticleCode" name="popupUdmListOrder_filterArticleCode" placeholder="Codice articolo" value="" data-return-action="popupUdmListOrder_search_btn" />
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" id="popupUdmListOrder_search_btn" onclick="popupUdmListOrder_search(); return false;"><i class="icon-white icon-search" ></i> Cerca</button>
			</div>
			<div>
				<button class="span2 pull-right"  onclick="popupUdmListOrder_addArticles(); return false;"><i class="icon-plus"></i>Aggiungi</button>
			</div>
		</div>

		<div class="results">
			
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			
			
			<div class="checkall_label" colspan="3">Seleziona tutti</div>
			<table id="popupUdmListsOrder_table" class="table table-bordered table-condensed table-striped" width="100%">
				<thead>
					<tr>
						<th style="text-align: center; width: 50px;"><input type="checkbox" id="popupUdmListsOrder_checkall" class="popupUdmListsOrder_checkall" value="1" /></th>
						<th style="text-align: center;">UDM</th>
						<th style="text-align: center;">Articolo</th>
						<th>Descrizione</th>
						<th style="text-align: center;">Quantità</th>
						<th style="text-align: center;">Ubicazione</th>
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
	
	popupUdmList_stockId = <?php echo $this->stockId; ?>;

	jQuery(document).ready(function () {
		
		popupUdmListOrder_refreshUdms(<?php echo $this->stockId; ?>);	
		
		
	});
	jQuery(document).ready(function() {
		jQuery('#popupUdmListsOrder_checkall').click(function() {  
			if(this.checked) {
				jQuery('.popupUdmListOrder_checkbox').each(function() {
					this.checked = true;  
					 jQuery('.checkall_label').text('Deseleziona tutti') ;
				});
			}else{ 
				jQuery('.popupUdmListOrder_checkbox').each(function() {
                this.checked = false;
				jQuery('.checkall_label').text('Seleziona tutti') ;
				});
			}
		}); 
	});
	    
</script>