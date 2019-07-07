<?php 
	//Logging
	define("DM_LOG_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs');
	
	//API
	define("DM_API_SHAREDSECRET", "dmdFoldherd");
	
	//Version
	define("DM_APP_VERSION", "0.1.1");
	
	//Log
	define("FH_LOG_ENABLED", true);
	
	class DMAppConfig {
		
		public $db_driver = 'mysqli';
		public $db_host = 'localhost';
		public $db_user = '';
		public $db_password = '';
		public $db_database = '';
		public $db_prefix = 'fh_';
		
		public $user_table = 'fh_user';
		public $user_idField = 'user_id';
		public $user_usernameField = 'username';
		public $user_passwordField = 'password';
		
		public $appdata_table = 'fh_appdata';
		public $appdata_idField = 'ckey';
		public $appdata_valueField = 'cvalue';
		
		public $acl_groupPrivilegesTable = 'fh_acl_group_privilege';
		public $acl_userGroupsTable = 'fh_acl_user_group';
		public $acl_privilegesTable = 'fh_acl_privileges';
		public $acl_groupsTable = 'fh_acl_group';
		
		public $base_path = DM_APP_PATH;
		public $base_url = '';
		
		public $secret = 's784t2f2983x23';
		
		public $ftp_host = '127.0.0.1';
		public $ftp_user = '';
		public $ftp_password = '';
		public $ftp_articles_base = '';
		public $ftp_chargelist_base = 'chargelists';
		public $ftp_movements_export_base = '';
		
		public $ftpx_host = '';
		public $ftpx_user = '';
		public $ftpx_password = '';
		public $ftpx_articles_base = '';
		public $ftpx_chargelist_base = '';
		public $ftpx_movements_export_base = '';

		
	}
	
	require_once(DM_APP_PATH . DS . 'config_srv.php');
?>
