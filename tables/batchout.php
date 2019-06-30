<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableBatchOut extends DMTable
	{ 
		var $batch_out_id = null; 
		var $batch_out_code = null; 
		
		function __construct()
		{
			parent::__construct( 'fh_batch_out', 'batch_out_id');
		}
		
		function loadFromBatchOutCode($batchOutCode) {
			
			$myQuery = "
				SELECT batch_out_id
				FROM fh_batch_out
				WHERE batch_out_code = '" . DMDatabase::escape($batchOutCode) . "'
			";
			
			$batchOutId = DMDatabase::loadResult($myQuery);
			
			if ($batchOutId) {
				return self::load($batchOutId);
			} else {
				return false;
			}
			
		}
	}

?>