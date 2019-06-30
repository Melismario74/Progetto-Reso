<div id="fhArrival" class="container">
	<div class="headerbar">
		<h2>Documento in entrata</h2>
	</div>
	<div class="toolbar">
		<button id="fhArrival_edit_btn" class="btn btn-primary fhArrival_viewMode" onclick="fhArrival_editMode(); return false;">Modifica</button>
		<button id="fhArrival_edit_btn" class="btn btn-danger fhArrival_viewMode" onclick="fhArrival_delete(); return false;">Elimina</button>
		<button id="fhArrival_save_btn" class="btn btn-success fhArrival_editMode" onclick="fhArrival_save(); return false;">Salva</button>
		<button id="fhArrival_close_btn" class="btn pull-right" onclick="fhArrival_close(); return false;">Chiudi</button>
		
		<button id="fhArrival_print_btn" class="btn btn-warning pull-right" onclick="fhArrival_print(); return false;" style="margin-right: 5px;">Stampa</button>
	</div>
	
	<div class="main">
	
		<div class="progress progress-striped active" style="display: none;">
		    <div class="bar" style="width: 100%;"></div>
		</div>
		<div class="row" style="text-align: right">					
				<a href="#" onclick="fhArrival_archivedToggle(); return false;" id="fhArrival_btnArchivedToggle"><span id="fhArrival_labelArchived">LAVORABILE</span></a>
		</div>
		
		<form id="fhArrival_form" class="form-horizontal">
			<div id="fhArrival_tabs">
				<ul class="nav nav-tabs">
    				<li><a href="#fhArrival_info" data-toggle="tab">Dati generali</a></li>
    				<li><a href="#fhArrival_details" data-toggle="tab">Righe documento</a></li>
    			</ul>
				<div id="fhArrival_tabs_content" class="tab-content">
					<div id="fhArrival_info" class="tab-pane">
						<div class="row">
							<div class="span6">
								<div class="label">
									ARRIVO N°
								</div>
								<div class="value">
									<div class="fhArrival_editMode">
										<input class="fhArrival_editField" type="text" id="fhArrival_editArrivalCode" name="arrivalCode"  />
									</div>
									<div class="fhArrival_viewMode">
										<span class="fhArrival_viewField" id="fhArrival_viewArrivalCode" data-view-for="fhArrival_editArrivalCode"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									Data
								</div>
								<div class="value">
									<div class="fhArrival_editMode">
										<input class="fhArrival_editField" type="text" id="fhArrival_editArrivalDate" name="arrivalDate" required="required" />
									</div>
									<div class="fhArrival_viewMode">
										<span class="fhArrival_viewField" id="fhArrival_viewArrivalDate" data-view-for="fhArrival_editArrivalDate"></span>
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
									<div class="fhArrival_editMode">
										<input class="fhArrival_editField" type="text" id="fhArrival_editSubject" name="subject" list="subjectList" />
										<datalist id="subjectList">
											<option value="RESO">
											<option value="RESPINTO">
										</datalist>
									</div>
									<div class="fhArrival_viewMode">
										<span class="fhArrival_viewField" id="fhArrival_viewSubject" data-view-for="fhArrival_editSubject"></span>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="label">
									VETTORE
								</div>
								<div class="value">
									<div class="fhArrival_editMode">
										<select id="fhArrival_editVectorName" name="vectorName" class="fhArrival_editField">
											<option value="">Vettore</option>
										</select>
									</div>
									<div class="fhArrival_viewMode">
										<span class="fhArrival_viewField" id="fhArrival_viewVectorName" data-view-for="fhArrival_editVectorName"></span>
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
									<div class="fhArrival_editMode">
										<textarea class="fhArrival_editField" type="text" id="fhArrival_editNotes" name="notes"></textarea>
									</div>
									<div class="fhArrival_viewMode">
										<span class="fhArrival_viewField" id="fhArrival_viewNotes" data-view-for="fhArrival_editNotes"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="fhArrival_details" class="tab-pane">
						<div class="fhArrival_editMode" >
							<button class="btn btn-primary pull-right" onclick="fhArrival_addLdv(); return false;" style="margin-bottom: 10px;" >Inserisci LDV</button>
						</div>
						<table  class="table table-condensed table-bordered table-striped" width="100%">
							<thead>
								<tr>
									<th>LDV</th>
									<th>Data</th>
									<th>Mittente</th>
									<th>Bancali</th>
									<th>Colli.</th>
									<th>Note.</th>
									<th class="fhArrival_editMode"></th>
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
	
		jQuery('#fhArrival_form').validate();
		
		jQuery('#fhArrival_tabs li a:first').tab("show");
		
		<?php if ($this->arrival->arrival_id == -1) { ?>
		fhArrival_newArrival();
		<?php } else { ?>
		fhArrival_editArrival(<?php echo $this->arrival->arrival_id; ?>)
		<?php } ?>
		
		jQuery('.row').cascadingDropdown({
    	    selectBoxes: [
    	        {
    	            selector: '#fhArrival_editVectorName',
    	            url: '<?php echo DMUrl::getCurrentBaseUrl(); ?>?controller=vector&type=json&task=jsonGetVectors',
    	            textKey: 'name',
    	            valueKey: 'name',
    	            paramName: 'vectorName',
    	            defaultKey: '',
    	            dataElement: 'vectors'
    	        }
    	    ]
    	});
		
		
    	
    	jQuery('#fhArrival_editArrivalDate').datepicker(
    	    {
    	    	format: "dd/mm/yyyy",
    	    	weekStart: 1,
    	    	autoclose: true
    	    }
    	);
	});
	
</script>