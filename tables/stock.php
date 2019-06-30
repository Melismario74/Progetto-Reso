<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableStock extends DMTable
	{ 
		var $stock_id = null; 
		var $name = null; 
		var $stock_code = null;
		var $stock_code_short = null;
		
		function __construct()
		{
			parent::__construct( 'fh_stock', 'stock_id');
		}
	}

?>