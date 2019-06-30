<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableArrivalRow extends DMTable { 
	
		var $arrival_row_id = null;
		var $arrival_id = null; 
		var $ldv_id = null; 
		var $ldv_code = null; 
		var $carton = null; 
		var $pallet = null; 

		
		function __construct()
		{
			parent::__construct( 'fh_arrival_row', 'arrival_row_id');
		}
		
	}

?>