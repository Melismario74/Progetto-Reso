<div id="fhUdm" class="container">
    <div class="headerbar">
		<h2>UDM</h2>
	</div>
	<div class="main search">
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhUdm_filterArticleCode" name="fhUdm_filterArticleCode" placeholder="Codice articolo" value="" data-return-action="fhUdm_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhUdm_filterUdmCode" name="fhUdm_filterUdmCode" placeholder="UDM" value="" data-return-action="fhUdm_search_btn" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="fhUdm_filterUbicazione" name="fhUdm_filterUbicazione" placeholder="Ubicazione" value="" data-return-action="fhUdm_search_btn" />
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" id="fhUdm_search_btn" onclick="fhUdm_search(); return false;"><i class="icon-white icon-search" ></i> Cerca</button>
			</div>			
			<div class="span2">
				<button class="btn btn-warning" onclick="fhUdms_exportCSV(); return false;" id="fhUdms_exportCSV_btn">Esporta CSV</button>
			</div>			 
			<div class="span1">
				<button id="fhUdm_save_btn" class="btn btn-success" onclick="fhUdm_saveUdm(); return false;"  >Salva</button>
			</div>
			<div class="span1_2">
				<button id="fhUdm_print_btn" class="btn btn-warning" onclick="fhUdm_printUdm(); return false;"  >Stampa</button>
			</div>
			<div class="span1_2">
				<button id="fhUdm_edit_btn" class="btn btn-danger" onclick="fhUdm_deleteUdm(); return false;" >Elimina</button>
			</div>
		</div>
		<div class="results">
			<div class="progress progress-striped active" style="display: none">
				<div class="bar" style="width: 100%;"></div>
			</div>		
			<div class="checkall_label" colspan="3">Seleziona tutti</div>
			<table id="fhUdms_table" class="table table-bordered table-condensed table-striped" width="100%">
				<thead>
					<tr>
						<th style="text-align: center; width: 50px;"><input type="checkbox" id="fhUdm_checkall" class="fhUdm_checkall" value="1" /></th>
						<th style="text-align: center;">UDM</th>
						<th style="text-align: center;">Articolo</th>
						<th>Descrizione</th>
						<th style="text-align: center;">Quantità</th>
						<th style="text-align: center;">Ubicazione</th>
					</tr>
				</thead>
				<tbody></tbody>
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
		jQuery('#fhUdm_checkall').click(function() {  
			if(this.checked) {
				jQuery('.fhUdm_checkbox').each(function() {
					this.checked = true;  
					 jQuery('.checkall_label').text('Deseleziona tutti') ;
				});
			}else{ 
				jQuery('.fhUdm_checkbox').each(function() {
                this.checked = false;
				jQuery('.checkall_label').text('Seleziona tutti') ;
				});
			}
		}); 
	});
	jQuery(document).ready(function () {
		
			fhUdm_search();
		
	});
	    
</script>