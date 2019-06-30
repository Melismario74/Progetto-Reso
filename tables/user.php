<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableUser extends DMTable
	{ 
		var $user_id = null; 
		var $username = null;  
		var $password = null; 
		var $name = null;
		var $settings = null;
		
		function __construct()
		{
			parent::__construct( 'fh_user', 'user_id');
		}
		
		function setPassword($newPassword) {
		
			if (!DMUser::validatePassword($newPassword)) {
				return false;
			} else {
				$config = new DMConfig();
				$salt = $config->secret;
				
				$this->password = md5($newPassword . $salt);
				
				return true;
			}
			
		}
		
		function validate() {
		
			$this->_errors = array();
			
			if (!DMUser::validateUsername($this->email)) {
				$this->_errors[] = 'Username';
				return false;
			}
			if (strlen($this->name) < 3) {
				$this->_errors[] = 'Name';
				return false;
			}
			
			return true;
			
		}
		
		function delete() {
			
			DMAcl::setAclUserGroups($this->user_id, array());
			
			return parent::delete();
			
		}
	}

?>