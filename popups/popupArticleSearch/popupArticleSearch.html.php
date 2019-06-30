<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupArticleSearch_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<div class="search">
		<div class="filters row">
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="popupArticleSearch_filterArticleCode" name="popupArticleSearch_filterArticleCode" placeholder="Codice articolo" value="" />
			</div>
			<div class="span1_5">
				<input style="width: 100%;" type="text" id="popupArticleSearch_filterEanCode" name="popupArticleSearch_filterEanCode" placeholder="Codice EAN" value="<?php echo $this->eanCode; ?>" />
			</div>
			<div class="span3">
				<input style="width: 100%;" type="text" id="popupArticleSearch_filterName" name="popupArticleSearch_filterName" placeholder="Descrizione" value="" />
			</div>
			<div class="span1_5">
				<button class="btn btn-primary" onclick="popupArticleSearch_search(); return false;"><i class="icon-white icon-search"></i> Cerca</button>
			</div>
		</div>
		
		<div class="results">
			<div class="progress progress-striped active">
				<div class="bar" style="width: 100%;"></div>
			</div>
			<table id="popupArticleSearch_results_table" width="100%" class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th width="75px;" style="text-align: center">#</th>
						<th width="75px;" style="text-align: center"></th>
						<th>Descrizione</th>
						<th width="100px;" style="text-align: right">Conf./Cart.</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align: center">
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>

<script>

	jQuery(document).ready(function () {
	
		popupArticleSearch_search();
    	
	});
	    
</script>