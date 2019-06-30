<div id="fhOrder" class="container">
	<div class="headerbar">
		<h2>Lista di prelievo</h2>
	</div>
	<div class="toolbar">
		<button id="fhOrder_edit_btn" class="btn btn-primary fhOrder_viewMode" onclick="fhOrder_editMode(); return false;">Modifica</button>
		<button id="fhOrder_edit_btn" class="btn btn-danger fhOrder_viewMode" onclick="fhOrder_delete(); return false;">Elimina</button>
		<button id="fhOrder_save_btn" class="btn btn-success fhOrder_editMode" onclick="fhOrder_save(); return false;">Salva</button>
		<button id="fhOrder_close_btn" class="btn pull-right" onclick="fhOrder_close(); return false;">Chiudi</button>
		
		<button id="fhOrder_print_btn" class="btn btn-warning pull-right" onclick="fhOrder_print(); return false;" style="margin-right: 5px;">Stampa</button>
	</div>
	
	<div class="main">
	
		<div class="progress progress-striped active" style="display: none;">
		    <div class="bar" style="width: 100%;"></div>
		</div>
		<div class="row" style="text-align: right">					
				<a href="#" onclick="fhOrder_archivedToggle(); return false;" id="fhOrder_btnArchivedToggle"><span id="fhOrder_labelArchived">ARCHIVIA</span></a>
		</div>
		
		<form id="fhOrder_form" class="form-horizontal">
			
			<div id="fhOrder_tabs">
				<ul class="nav nav-tabs">
    				<li><a href="#fhOrder_info" data-toggle="tab">Dati generali</a></li>
    				<li><a href="#fhOrder_details" data-toggle="tab">Righe documento</a></li>
    			</ul>
				
    			<div id="fhOrder_tabs_content" class="tab-content">
					<div id="fhOrder_info" class="tab-pane">
						<div class="row">
							<div class="span6">
								<div class="label">
									RICHIESTA N°
								</div>
								<div class="value">
									<div class="fhOrder_editMode">
										<input class="fhOrder_editField" type="text" id="fhOrder_editOrderCode" name="orderCode" />
									</div>
									<div class="fhOrder_viewMode">
										<span class="fhOrder_viewField" id="fhOrder_viewOrderCode" data-view-for="fhOrder_editOrderCode"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									Data
								</div>
								<div class="value">
									<div class="fhOrder_editMode">
										<input class="fhOrder_editField" type="text" id="fhOrder_editOrderDate" name="orderDate" required="required" />
									</div>
									<div class="fhOrder_viewMode">
										<span class="fhOrder_viewField" id="fhOrder_viewOrderDate" data-view-for="fhOrder_editOrderDate"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="span6">
								<div class="label">
									Causale
								</div>
								<div class="value">
									<div class="fhOrder_editMode">
										<input class="fhOrder_editField" type="text" id="fhOrder_editSubject" name="subject" required="required" />
									</div>
									<div class="fhOrder_viewMode">
										<span class="fhOrder_viewField" id="fhOrder_viewSubject" data-view-for="fhOrder_editSubject"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									Ufficio richiedente
								</div>
								<div class="value">
									<div class="fhOrder_editMode">
										<input class="fhOrder_editField" type="text" id="fhOrder_editClientName" name="clientName" required="required" />
									</div>
									<div class="fhOrder_viewMode">
										<span class="fhOrder_viewField" id="fhOrder_viewClientName" data-view-for="fhOrder_editClientName"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="span6">
								<div class="label">
									Note
								</div>
								<div class="value">
									<div class="fhOrder_editMode">
										<textarea class="fhOrder_editField" type="text" id="fhOrder_editNotes" name="notes"></textarea>
									</div>
									<div class="fhOrder_viewMode">
										<span class="fhOrder_viewField" id="fhOrder_viewNotes" data-view-for="fhOrder_editNotes"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="fhOrder_details" class="tab-pane">
						<div class="fhOrder_editMode" style="margin-bottom: 10px;">
							<button class="btn btn-primary pull-right" onclick="fhOrder_showUdmStatus(3); return false;" style="margin-right: 5px;"><i class="icon-plus icon-white"></i> Ibrido</button>
							<button class="btn btn-primary pull-right" onclick="fhOrder_showUdmStatus(1); return false;" style="margin-right: 5px;"><i class="icon-plus icon-white"></i> Buono</button>
						</div>
						<table class="table table-condensed table-bordered table-striped" width="100%">
							<thead>
								<tr>
									<th>Udm</th>
									<th>Udm Storico</th>
									<th>Articolo</th>
									<th>Descrizione</th>
									<th>Conf.</th>
									<th>Ubicazione</th>
									<th class="fhOrder_editMode"></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>

	jQuery(document).ready(function() {
	
		jQuery('#fhOrder_form').validate();
		
		jQuery('#fhOrder_tabs li a:first').tab("show");
		
		<?php if ($this->order->order_id == -1) { ?>
		fhOrder_newOrder();
		<?php } else { ?>
		fhOrder_editOrder(<?php echo $this->order->order_id; ?>)
		<?php } ?>
		
		
    	
    	jQuery('#fhOrder_editOrderDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
	});
	
</script>