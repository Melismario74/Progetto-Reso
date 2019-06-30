<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableOrderRow extends DMTable { 
	
		var $order_row_id = null;
		var $order_id = null; 
		var $article_id = null; 
		var $article_code = null; 
		var $description = null; 
		var $quantity_units = null; 
		var $quantity_packages = null; 
		var $stock_id = null; 
		var $udm_id = null;
		var $udm_code = null;
		var $udm_code_old = null;
		var $ubicazione = null;
		
		function __construct()
		{
			parent::__construct( 'fh_order_row', 'order_row_id');
		}
		
	}

?>