<div id="fhFile" class="container">
	<div class="headerbar">
		<h2>File</h2>
	</div>

	<form action='index.php?controller=file&task=jsonImportFTPFile&type=json' id="uploadForm" class="form-horizontal" name="uploadForm" method="post" enctype="multipart/form-data">
	       <div id="uploadform" class="well">
		   <fieldset id="upload-noflash" class="actions">
		       <div class="control-group">
			   <div class="control-label">
			       <label for="upload-file" class="control-label">Carica file</label>
			   </div>
			   <div class="controls">
			       <input type="file" id="upload-file" name="upload-file" multiple /><button class="btn btn-primary" id="upload-submit"><i class="icon-upload icon-white"></i> Inizio caricamento</button>
			       <p class="help-block">Carica file (Dimensioni massime: 10MB)</p>
			   </div>
		       </div>
		   </fieldset>
	       </div>
	</form>
</div>