<div id="fhInvoice" class="container">
	<div class="headerbar">
		<h2>Documento di uscita</h2>
	</div>
	<div class="toolbar">
		<button id="fhInvoice_edit_btn" class="btn btn-primary fhInvoice_viewMode" onclick="fhInvoice_editMode(); return false;">Modifica</button>
		<button id="fhInvoice_edit_btn" class="btn btn-danger fhInvoice_viewMode" onclick="fhInvoice_delete(); return false;">Elimina</button>
		<button id="fhInvoice_save_btn" class="btn btn-success fhInvoice_editMode" onclick="fhInvoice_save(); return false;">Salva</button>
		<button id="fhInvoice_close_btn" class="btn pull-right" onclick="fhInvoice_close(); return false;">Chiudi</button>
		
		<button id="fhInvoice_print_btn" class="btn btn-warning pull-right" onclick="fhInvoice_print(); return false;" style="margin-right: 5px;">Stampa</button>
	</div>
	
	<div class="main">
	
		<div class="progress progress-striped active" style="display: none;">
		    <div class="bar" style="width: 100%;"></div>
		</div>
		<div class="row" style="text-align: right">					
				<a href="#" onclick="fhInvoice_archivedToggle(); return false;" id="fhInvoice_btnArchivedToggle"><span id="fhInvoice_labelArchived">ARCHIVIA</span></a>
		</div>
		
		<form id="fhInvoice_form" class="form-horizontal">
			
			<div id="fhInvoice_tabs">
				<ul class="nav nav-tabs">
    				<li><a href="#fhInvoice_info" data-toggle="tab">Dati generali</a></li>
    				<li><a href="#fhInvoice_details" data-toggle="tab">Righe documento</a></li>
    			</ul>
				
    			<div id="fhInvoice_tabs_content" class="tab-content">
					<div id="fhInvoice_info" class="tab-pane">
						<div class="row">
							<div class="span6">
								<div class="label">
									RICHIESTA N°
								</div>
								<div class="value">
									<div class="fhInvoice_editMode">
										<input class="fhInvoice_editField" type="text" id="fhInvoice_editInvoiceCode" name="invoiceCode" />
									</div>
									<div class="fhInvoice_viewMode">
										<span class="fhInvoice_viewField" id="fhInvoice_viewInvoiceCode" data-view-for="fhInvoice_editInvoiceCode"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									Data
								</div>
								<div class="value">
									<div class="fhInvoice_editMode">
										<input class="fhInvoice_editField" type="text" id="fhInvoice_editInvoiceDate" name="invoiceDate" required="required" />
									</div>
									<div class="fhInvoice_viewMode">
										<span class="fhInvoice_viewField" id="fhInvoice_viewInvoiceDate" data-view-for="fhInvoice_editInvoiceDate"></span>
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
									<div class="fhInvoice_editMode">
										<input class="fhInvoice_editField" type="text" id="fhInvoice_editSubject" name="subject" required="required" />
									</div>
									<div class="fhInvoice_viewMode">
										<span class="fhInvoice_viewField" id="fhInvoice_viewSubject" data-view-for="fhInvoice_editSubject"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									Ufficio richiedente
								</div>
								<div class="value">
									<div class="fhInvoice_editMode">
										<input class="fhInvoice_editField" type="text" id="fhInvoice_editClientName" name="clientName" required="required" />
									</div>
									<div class="fhInvoice_viewMode">
										<span class="fhInvoice_viewField" id="fhInvoice_viewClientName" data-view-for="fhInvoice_editClientName"></span>
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
									<div class="fhInvoice_editMode">
										<textarea class="fhInvoice_editField" type="text" id="fhInvoice_editNotes" name="notes"></textarea>
									</div>
									<div class="fhInvoice_viewMode">
										<span class="fhInvoice_viewField" id="fhInvoice_viewNotes" data-view-for="fhInvoice_editNotes"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="fhInvoice_details" class="tab-pane">
						<div class="fhInvoice_editMode" style="margin-bottom: 10px;">
							<button class="btn btn-primary pull-right" onclick="fhInvoice_addItems(2); return false;" ><i class="icon-plus icon-white"></i> Scarto</button>
							<button class="btn btn-primary pull-right" onclick="fhInvoice_showUdmStatus(3); return false;" style="margin-right: 5px;"><i class="icon-plus icon-white"></i> Ibrido</button>
							<button class="btn btn-primary pull-right" onclick="fhInvoice_showUdmStatus(1); return false;" style="margin-right: 5px;"><i class="icon-plus icon-white"></i> Buono</button>
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
									<th class="fhInvoice_editMode"></th>
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
	
		jQuery('#fhInvoice_form').validate();
		
		jQuery('#fhInvoice_tabs li a:first').tab("show");
		
		<?php if ($this->invoice->invoice_id == -1) { ?>
		fhInvoice_newInvoice();
		<?php } else { ?>
		fhInvoice_editInvoice(<?php echo $this->invoice->invoice_id; ?>)
		<?php } ?>
		
		
    	
    	jQuery('#fhInvoice_editInvoiceDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
	});
	
</script>