<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableVector extends DMTable
	{ 
		var $vector_id = null; 
		var $name = null;
		var $settings = null;
		
		function __construct()
		{
			parent::__construct( 'fh_vector', 'vector_id');
		}
		
		function delete() {
			
			return parent::delete();
			
		}
	}

?>