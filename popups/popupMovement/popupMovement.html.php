<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="popupMovement_label"><?php echo $this->title; ?></h3>
</div>
<div class="modal-body">

	<form id="popupMovement_form" class="form-horizontal">
    	<div class="control-group form-inline">
    		<label class="control-label">Articolo</label>
    		<div class="controls" style="padding-top: 5px">
    			<span id="popupMovement_articleName"></span>
    		</div>
    	</div>
    	<div class="control-group form-inline">
    		<label class="control-label">Confezioni</label>
    		<div class="controls">
    			<input type="text" id="popupMovement_editUnits" name="popupMovement_editUnits" value="0" required="required"/>
    		</div>
    	</div>
    	<div class="control-group form-inline">
    		<label class="control-label">Cartoni</label>
    		<div class="controls">
    			<input type="text" id="popupMovement_editPackages" name="popupMovement_editPackages" value="0" required="required" />
    		</div>
    	</div>
    	<div class="control-group form-inline">
    		<label class="control-label">Lista di carico</label>
    		<div class="controls">
    			<select id="popupMovement_editChargelist" name="chargelistId" class="popupMovement_editChargelist">
		        	<option value="">Lista di carico</option>
		     	</select>
    		</div>
    	</div>
    	<div class="control-group form-inline">
    		<label class="control-label">Da</label>
    		<div class="controls">
    			<select id="popupMovement_editStockFrom" name="stockIdFrom" class="popupMovement_editStockFrom">
		        	<option value="">Magazzino di partenza</option>
		     	</select>
    		</div>
    	</div>
    	<div class="control-group form-inline">
    		<label class="control-label">A</label>
    		<div class="controls">
    			<select id="popupMovement_editStockTo" name="stockIdTo" class="popupMovement_editStockTo">
		        	<option value="">Magazzino di destinazione</option>
		     	</select>    			
    		</div>
    	</div>
    </form>
    
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	<button class="btn btn-success" onclick="popupMovement_saveMovement(); return false;">Salva</button>
</div>

<script>

	jQuery(document).ready(function () {
	
		jQuery('#popupMovement_form').validate();
	
		popupMovement_loadArticle(<?php echo $this->articleId; ?>);
		
		jQuery('#popupMovement_editCreatedDateDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
    	
    	jQuery('#popupMovement_form').cascadingDropdown({
    	    selectBoxes: [
    	        {
    	            selector: '.popupMovement_editStockFrom',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=stock&type=json&task=jsonGetStocks',
    	            textKey: 'name',
    	            valueKey: 'stock_id',
    	            paramName: 'stockIdFrom',
    	            defaultKey: '',
    	            dataElement: 'stocks'
    	        },
    	        {
    	            selector: '.popupMovement_editStockTo',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=stock&type=json&task=jsonGetStocks',
    	            textKey: 'name',
    	            valueKey: 'stock_id',
    	            paramName: 'stockIdTo',
    	            defaultKey: '',
    	            dataElement: 'stocks'
    	        },
    	        {
    	            selector: '.popupMovement_editChargelist',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=chargelist&type=json&task=jsonGetChargelists',
    	            textKey: 'chargelist_code',
    	            valueKey: 'chargelist_id',
    	            paramName: 'chargelistId',
    	            defaultKey: '',
    	            dataElement: 'chargelists'
    	        }
    	    ]
    	});
    	
    	
	});
	    
</script>