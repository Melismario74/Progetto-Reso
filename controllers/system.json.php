<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class SystemJsonController extends DMJsonController {
		
		/**
		Backup Database
		index.php?controller=system&task=jsonDailyBackup&type=json
		 **/
		function jsonDailyBackup() {
			
			
			/*require_once(DM_APP_PATH . DS . 'helpers' . DS . 'fhhelper.php');	


			//Se il file è già presente in FTP, non faccio nulla
			$dmConfig = new DMConfig();

			FHHelper::log("exportMovementsCSV", "Starting daily export");

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovementsCSV", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				FHHelper::log("exportMovementsCSV", "ERROR: no login");
				parent::outputError(-110);
			}
			$targetDate = DMInput::getString('date', DMFormat::formatDate(date('Y-m-d'), 'Y-m-d', 'Y-m-d'));
			$targetDateFile = DMFormat::formatDate($targetDate, 'dmy', 'Y-m-d');
			
			$fileName = $targetDate . '_RESOMOVMAG.sql';
			$remoteLocation = $dmConfig->ftpx_movements_export_base . '/' . $fileName;
			if (!ftp_put($connection, $remoteLocation, DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . 'dumpy.sql',  FTP_ASCII)) {
				echo "Nothing to be done, file already exists remotely";
				FHHelper::log("exportMovementsCSV", "ERROR: file already exists ($remoteLocation)");
				exit;
			}
			
			ftp_close($connection);
			
			*/
			/* $dmConfig = new DMConfig();
			
			$dbhost = $dmConfig->db_host;
			$dbuser = $dmConfig->db_user;
			$dbpass = $dmConfig->db_password;
			$dbname = 'backup_file';
			
			$conn = mysql_connect($dbhost, $dbuser, $dbpass);
   
		    if(! $conn ) {
			  die('Could not connect: ' . mysql_error());
		    }
	
		    $table_name = "fh_article";
		    $backup_file  = $dbname . date("Y-m-d-H-i-s") . '.sql';
		    $sql = "SELECT * INTO OUTFILE '$backup_file'  FROM $table_name ";
   
		    mysql_select_db('reso');
		    $retval = mysql_query( $sql, $conn );
		   
		    if(! $retval ) {
			  die('Could not take data backup: ' . mysql_error());
		    }
		   
		    mysql_close($conn);  */
			
			$dmConfig = new DMConfig();
						
			$dbhost = $dmConfig->db_host;
			$dbuser = $dmConfig->db_user;
			$dbpass = $dmConfig->db_password;
			$dbdatabase = $dmConfig->db_database;
			$backupFile = 'backup_file' . date("Y-m-d-H-i-s") . '.sql';
			$localBackup = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $backupFile;
			
			
					
			system("/bin/sh/mysqldump -hmysqlhost -uuiogxus1_reso -pScienze1973 uiogxus1_reso | gzip -9> ddd.sql.gz");
			
			
			
		    /* require_once(DM_APP_PATH . DS . 'helpers' . DS . 'fhhelper.php');	


			//Se il file è già presente in FTP, non faccio nulla

			FHHelper::log("exportMovementsCSV", "Starting daily export");

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovementsCSV", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				FHHelper::log("exportMovementsCSV", "ERROR: no login");
				parent::outputError(-110);
			}
			
			$remoteLocation = $dmConfig->ftpx_movements_export_base . '/' . $backupFile ;
			if (!ftp_put($connection, $remoteLocation, $localBackup ,  FTP_ASCII)) {
				echo "Nothing to be done, file already exists remotely";
				FHHelper::log("exportMovementsCSV", "ERROR: file already exists ($remoteLocation)");
				exit;
			}
			
			ftp_close($connection); */
		  
			
			parent::outputResult(0);
		}
		//D:\xampp\htdocs\Pubblicazione\resp v.0.1.5\mysqldump -hlocalhost -uroot -pmmelism00 reso> bbb.sql
			

		/**
		Esporta i movimenti in TXT
		index.php?controller=system&task=jsonDailyExportMovements&type=json
		 **/
		function jsonDailyExportMovements() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'movementhelper.php');

			//Se il file è già presente in FTP, non faccio nulla
			$dmConfig = new DMConfig();

			FHHelper::log("exportMovements", "Starting daily export");

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovements", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				FHHelper::log("exportMovements", "ERROR: no login");
				parent::outputError(-110);
			}
			$targetDate = DMInput::getString('date', DMFormat::formatDate(date('Y-m-d'), 'Y-m-d', 'Y-m-d'));
			$targetDateFile = DMFormat::formatDate($targetDate, 'dmy', 'Y-m-d');
			
			$fileName = $targetDate . 'RESOMOVMAG.txt';
			$remoteLocation = $dmConfig->ftpx_movements_export_base . '/' . $fileName;
			if (ftp_get($connection, DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . 'dummy.txt', $remoteLocation, FTP_ASCII)) {
				echo "Nothing to be done, file already exists remotely";
				FHHelper::log("exportMovements", "ERROR: file already exists ($remoteLocation)");
				exit;
			}
			
			ftp_close($connection);

			

			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;

			$searchParams['movementDateFrom'] = $targetDate . ' 00:00:00';
			$searchParams['movementDateTo'] = $targetDate . ' 23:59:59';
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['batchOutCode'] = DMInput::getString('batchOutCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['movementType'] = DMInput::getString('movementType', 'PROCESS');
			$searchParams['userId'] = DMInput::getInt('userId', -1);

			DMBase::loadModule('Appdata');
			$searchParams['movementIdFrom'] = DMAppData::getValue('movementsExport_idReached', 0);

			$movementIdMax = 0;
			$txtContent = FHMovementHelper::exportMovements($searchParams, $movementIdMax);

			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileName;

			if ($txtContent === '') {
				$txtContent = ' ';
			}
			if (!@file_put_contents($filePath, $txtContent)) {
				FHHelper::log("exportMovements", "ERROR: cannot export");
				parent::outputError(-200);
			}

			//Ora copio in FTP
			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovements", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);
			//ftp_pasv($connection, true);

			if (!$login) {
				FHHelper::log("exportMovements", "ERROR: no login");
				parent::outputError(-110);
			}
			
			FHHelper::log("exportMovements", "Starting upload");
			$trackErrors = ini_get('track_errors');
			ini_set('track_errors', 1);
			$ftpPutResult = ftp_put($connection, $remoteLocation, $filePath, FTP_ASCII);
			if (!$ftpPutResult) {
				FHHelper::log("exportMovements", "ERROR: cannot upload " . $php_errormsg);
   				ini_set('track_errors', $trackErrors);
				parent::outputError(-210);
			}

			DMAppData::setValue('movementsExport_idReached', $movementIdMax + 1);

			FHHelper::log("exportMovements", "Export OK");

			parent::outputResult(0);

		}
		
			/**
		Esporta i movimenti in CSV
		index.php?controller=system&task=jsonDailyExportMovementsCSV&type=json
		 **/
		 
		function jsonDailyExportMovementsCSV() {

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'fhhelper.php');	


			//Se il file è già presente in FTP, non faccio nulla
			$dmConfig = new DMConfig();

			FHHelper::log("exportMovementsCSV", "Starting daily export");

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovementsCSV", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				FHHelper::log("exportMovementsCSV", "ERROR: no login");
				parent::outputError(-110);
			} 
			$targetDate = DMInput::getString('date', DMFormat::formatDate(date('Y-m-d'), 'Y-m-d', 'Y-m-d'));
			$targetDateFile = DMFormat::formatDate($targetDate, 'dmy', 'Y-m-d');
			$targetName = DMFormat::formatDate($targetDate, 'ymd', 'Y-m-d');
			
			$fileName = 'RESB_'. $targetName . 'csv';
			$remoteLocation = $dmConfig->ftpx_movements_export_base . '/' . $fileName;
			if (!ftp_put($connection, $remoteLocation, DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . 'ummy.csv',  FTP_ASCII)) {
				echo "Nothing to be done, file already exists remotely";
				FHHelper::log("exportMovementsCSV", "ERROR: file already exists ($remoteLocation)");
				exit;
			}
			
			ftp_close($connection);

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'movementhelper.php');
 
			$searchParams = array();
			$searchParams['limit'] = 0;
			$searchParams['offset'] = 0;

			$searchParams['movementDateFrom'] = $targetDate . ' 00:00:00';
			$searchParams['movementDateTo'] = $targetDate . ' 23:59:59';
			$searchParams['batchInCode'] = DMInput::getString('batchInCode', '');
			$searchParams['batchOutCode'] = DMInput::getString('batchOutCode', '');
			$searchParams['eanCode'] = DMInput::getString('eanCode', '');
			$searchParams['articleCode'] = DMInput::getString('articleCode', '');
			$searchParams['movementType'] = DMInput::getString('movementType', 'PROCESS');
			$searchParams['stockId'] = DMInput::getInt('stockId',-1);
			$searchParams['userId'] = DMInput::getInt('userId', -1);

			/* 	
			DMBase::loadModule('Appdata');
			$searchParams['movementIdFrom'] = DMAppData::getValue('movementsExport_idReached', 0);
			*/
			
			$fileUrl = FHMovementHelper::exportDailyMovementsCSV($searchParams);
			
			$fileOrigName = basename($fileUrl);
			
			$filePath = DM_APP_PATH . DS . 'temp' . DS . 'export' . DS . $fileOrigName;

			
			
			//Ora copio in FTP
			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovementsCSV", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);
			//ftp_pasv($connection, true);

			if (!$login) {
				FHHelper::log("exportMovementsCSV", "ERROR: no login");
				parent::outputError(-110);
			}
			
			FHHelper::log("exportMovementsCSV", "Starting upload");
			/* $trackErrors = ini_get('track_errors');
			ini_set('track_errors', 1); */
			$ftpPutResult = ftp_put($connection, $remoteLocation, $filePath, FTP_ASCII);
			if (!$ftpPutResult) {
				FHHelper::log("exportMovementsCSV", "ERROR: cannot upload " . $php_errormsg);
   				// ini_set('track_errors', $trackErrors);
				parent::outputError(-210);
			}
			ftp_close($connection);
			// DMAppData::setValue('movementsExport_idReached', $movementIdMax + 1);

			FHHelper::log("exportMovementsCSV", "Export OK"); 
			
			parent::outputResult(0); 

		}
		
			/**
		Per pulire la cartella in remoto dei file creati come prova
		index.php?controller=system&task=cancellaFile&type=json
		 **/
		
		function cancellaFile() {
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'fhhelper.php');	


			//Se il file è già presente in FTP, non faccio nulla
			$dmConfig = new DMConfig();

			FHHelper::log("exportMovementsCSV", "Starting daily export");

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				FHHelper::log("exportMovementsCSV", "ERROR: no connection");
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				FHHelper::log("exportMovementsCSV", "ERROR: no login");
				parent::outputError(-110);
			}
			$fileName= array();
			
			$fileName[2] = '2018-08-29RESOMOVMAG.txt';
			$fileName[3] = '2018-08-30RESOMOVMAG1.csv';
            $fileName[4] = '2018-08-30RESOMOVMAG10.csv';
			$fileName[5] = '2018-08-30RESOMOVMAG11.csv';
			$fileName[6] = '2018-08-30RESOMOVMAG15.csv';
			$fileName[7] = '2018-08-30RESOMOVMAG2.csv';
			$fileName[8] = '2018-08-30RESOMOVMAG3.csv';
			$fileName[9] = '2018-08-30RESOMOVMAG4.csv';
			$fileName[10] = '2018-08-30RESOMOVMAG5.csv';
			$fileName[11] = '2018-08-30RESOMOVMAG6.csv';
			$fileName[12] = '2018-08-30RESOMOVMAG7.csv';
			$fileName[13] = '2018-08-30RESOMOVMAG8.csv';
			$fileName[14] = '2018-08-30RESOMOVMAG9.csv';
			$fileName[15] = 'OVMOVMAG.txt';
			$fileName[16] = 'OVMOVMAG1.txt';
			$fileName[17] = 'OVMOVMAG2.txt';
			$fileName[18] = 'OVMOVMAG3.txt'; 
			
			

			for($i=2;$i<=18;$i++) {
			$remoteLocation = $dmConfig->ftpx_movements_export_base . '/' . $fileName[$i] ;
			ftp_delete($connection, $remoteLocation);			
			}
			ftp_close($connection);
			
			parent::outputResult(0);

		}

		/**
		Importa gli articoli da file locato in FTP, e provvede successivamente ad importare le immagini
		index.php?controller=system&task=jsonImportArticles&type=json
		 **/
		function jsonImportArticles() {

			//Qui dovrei importare il file dall'FTP alla mia cartella uploads
			$dmConfig = new DMConfig();

			$connection = ftp_connect($dmConfig->ftpx_host, 21);

			if (!$connection) {
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftpx_user, $dmConfig->ftpx_password);

			if (!$login) {
				parent::outputError(-110);
			}

			$sourcePath = $dmConfig->ftpx_articles_base . '/ANAGRAFICA.TXT';
			$importPath = DM_APP_PATH . DS . 'uploads' . DS . 'ANAGRAFICA.TXT';

			DMLog::log('system', 'jsonImportArticles(): downloading import file...');
			if (!ftp_get($connection, $importPath, $sourcePath, FTP_ASCII)) {
				parent::outputError(-310);
			}

			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'articlehelper.php');
			DMLog::log('system', 'jsonImportArticles(): starting articles import...');
			$result = FHArticleHelper::importArticlesFromFile($importPath, false);

			DMLog::log('system', 'jsonImportArticles(): import results: ' . $result['success'] . ' success,  ' . $result['inserts'] . ' and ' . $result['fail'] . ' fails');

			parent::outputResult(0, $result);

			//Deve scaricare lo zip e scompattarlo in /media/articles

			exit;

		}

		/**
		Copia lo zip delle foto e lo scompatta opportunamente
		 **/
		function jsonImportArticlesPhotos() {

			//Qui dovrei importare il file dall'FTP alla mia cartella uploads
			$dmConfig = new DMConfig();

			$connection = ftp_connect($dmConfig->ftp_host, 21);

			if (!$connection) {
				parent::outputError(-300);
			}

			$login = ftp_login($connection, $dmConfig->ftp_user, $dmConfig->ftp_password);

			if (!$login) {
				parent::outputError(-110);
			}

			$sourcePath = $dmConfig->ftp_articles_base . '/foto_compresse.zip';
			$importPath = DM_APP_PATH . DS . 'uploads' . DS . 'foto_compresse.zip';

			DMLog::log('system', 'jsonImportArticlesPhotos(): downloading zip file...');
			if (!ftp_get($connection, $importPath, $sourcePath, FTP_BINARY)) {
				parent::outputError(-310);
			}

			DMLog::log('system', 'jsonImportArticlesPhotos(): inflating zip file...');

			$zip = new ZipArchive;
			if ($zip->open($importPath) === TRUE) {
				$zip->extractTo(DM_APP_PATH . DS . 'media' . DS . 'articles');
				$zip->close();
				DMLog::log('system', 'jsonImportArticlesPhotos(): OK!');
				parent::outputResult(0);
			} else {
				DMLog::log('system', 'jsonImportArticlesPhotos(): ERROR extracting zip!');
				parent::outputError(-1000, 'Extract failed');
			}

		}

	}
?>