<?php 

	//ini_set('memory_limit','512M');
	//ini_set('max_execution_time', '400');

	class DMConfig extends DMAppConfig {
		
		public $db_driver = 'mysqli';
		public $db_host = 'localhost';
		public $db_user = 'root';
		public $db_password = 'mmelism00';
		public $db_database = 'reso';
		public $db_prefix = 'fh_';
		
		public $user_table = 'fh_user';
		public $user_idField = 'user_id';
		public $user_usernameField = 'username';
		public $user_passwordField = 'password';
		
		public $acl_groupPrivilegesTable = 'fh_acl_group_privilege';
		public $acl_userGroupsTable = 'fh_acl_user_group';
		public $acl_privilegesTable = 'fh_acl_privileges';
		public $acl_groupsTable = 'fh_acl_group';
		
		public $base_path = DM_APP_PATH;
		public $base_url = 'http://localhost:8080/reso';
		
		public $secret = 's784t2f2983x23';
		
		public $ftp_host = '127.0.0.1';
		public $ftp_user = 'Mario';
		public $ftp_password = 'mmelism00';
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
	
	
?>