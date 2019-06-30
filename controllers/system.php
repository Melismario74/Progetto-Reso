<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class SystemController extends DMController {
	
		function display() {
			
			return false;
			
		}
		
		/**
			Importa gli articoli da file locato in FTP, e provvede successivamente ad importare le immagini
		**/
		function importArticles() {
			
			//Qui dovrei importare il file dall'FTP alla mia cartella uploads
			
			//Assumo che il file sia presente
			$importPath = DM_APP_PATH . DS . 'uploads' . DS . 'ANAGRAFICA.TXT';
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			$result = FHArticleHelper::importArticlesFromFile($importPath, false);
			
			print_r($result);
			
			//Deve scaricare lo zip e scompattarlo in /media/articles
			
			exit;
			
		}
		
		function debug() {
		
			$dmConfig = new DMConfig();

			$connection = ftp_connect($dmConfig->ftp_host, 21);

			if (!$connection) {
				echo "No connection";
			}

			$login = ftp_login($connection, $dmConfig->ftp_user, $dmConfig->ftp_password);

			if (!$login) {
				echo "No login";
			}
			
			$fileName = 'OVMOVMAG.txt';
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;
			$remoteLocation = $dmConfig->ftp_movements_export_base . '/' . $fileName;

			//Ora copio in FTP
			if (!ftp_put($connection, $remoteLocation, $filePath, FTP_ASCII)) {
				print_r(error_get_last());
				echo "No upload";
			}
			
			echo "OK";
			
			exit;
			
		}
		
	}
?>