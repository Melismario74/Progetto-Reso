<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableBatchIn extends DMTable
	{ 
		var $batch_in_id = null; 
		var $batch_in_code = null; 
		
		function __construct()
		{
			parent::__construct( 'fh_batch_in', 'batch_in_id');
		}
		
		function loadFromBatchInCode($batchInCode) {
			
			$myQuery = "
				SELECT batch_in_id
				FROM fh_batch_in
				WHERE batch_in_code = '" . DMDatabase::escape($batchInCode) . "'
			";
			
			$batchInId = DMDatabase::loadResult($myQuery);
			
			if ($batchInId) {
				return self::load($batchInId);
			} else {
				return false;
			}
			
		}
	}

?>