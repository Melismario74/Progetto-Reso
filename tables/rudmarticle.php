<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableRUdmArticle extends DMTable
	{ 
		var $udm_row_id = null;
		var $article_id = null;
		var $udm_id = null; 
		var $stock_id = null;  
		var $quantity_units = null;  
		var $quantity_packages = null;  
		var $committed_units = null; 

		
		function __construct()
		{
			parent::__construct( 'fh_r_udm_article', 'udm_row_id');
		}
		
		
		
		function delete() {
		
			$myUdm = DMTable::getInstance('Udm');
			$myUdm->load($this->udm_id);
			
			$deleteResult = parent::delete();
			
			return $deleteResult;
			
		}
	}

?>