<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class FHFileHelper {
		
		/**
			Scarica file richiesto in uploads
		**/
		static function downloadFTPFile($fileName) {
		
			$fileName = str_replace('..', '', $fileName);
			//
			//$dmConfig = new DMConfig();
			//
			//$connection = ftp_connect($dmConfig->ftp_host, 21);
			//
			//if (!$connection) {
			//	return false;
			//}
			//
			//$login = ftp_login($connection, $dmConfig->ftp_user, $dmConfig->ftp_password);
			//
			//if (!$login) {
			//	return false;
			//}
			
			$sourcePath = $_FILES['upload-file']['tmp_name'];
			$targetPath = DM_APP_PATH . DS . 'uploads' . DS . articles . DS . $fileName ;
			
			if (!move_uploaded_file( $sourcePath, $targetPath )) {
				return false;
			} else {
				return $targetPath;
			}
			
		}
		
	}
	
?>