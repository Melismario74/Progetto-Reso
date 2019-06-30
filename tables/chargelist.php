<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableChargelist extends DMTable
	{ 
		var $chargelist_id = null; 
		var $chargelist_code = null; 
		var $chargelist_date = null;
		var $archived = null;
		
		function __construct()
		{
			parent::__construct( 'fh_chargelist', 'chargelist_id');
		}
		
		function loadFromChargelistCode($chargelist) {
			
			$myQuery = "
				SELECT chargelist_id
				FROM fh_chargelist
				WHERE chargelist_code = '" . DMDatabase::escape($chargelist) . "'
			";
			
			$chargelistId = DMDatabase::loadResult($myQuery);
			
			if ($chargelistId) {
				return self::load($chargelistId);
			} else {
				return false;
			}
			
		}
	}

?>