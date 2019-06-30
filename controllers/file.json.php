<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FileJsonController extends DMJsonController {
		
		/**
			Importa il file indicato
		**/	
		function jsonImportFTPFile() {
			
				
			//Controllo i permessi
			if (!DMAcl::checkPrivilege("FH_ARTICLES_IMPORT")) {
				parent::outputError(-110);
			}
			$fileName = $_FILES['upload-file']['name'];
			
			
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'filehelper.php');
			$targetPath = FHFileHelper::downloadFTPFile($fileName);
			
			if (!$targetPath) {
				parent::outputError(-310);
			}	
		}
	}
	
?>