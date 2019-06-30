<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableLdvRow extends DMTable
	{ 
		var $ldv_row_id = null; 
		var $ldv_id = null;
		var $ddt_id = null;
	
		
		function __construct()
		{
			parent::__construct( 'fh_ldv_row', 'ldv_row_id');
		}
		
	}

?>