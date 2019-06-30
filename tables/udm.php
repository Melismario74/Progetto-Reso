<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableUdm extends DMTable
	{ 
		var $udm_id = null; 
		var $udm_code = null; 
		var $type = null;
		var $udm_code_int = null;
		var $udm_code_year = null;
		var $ubicazione = null;
		
		function __construct()
		{
			parent::__construct( 'fh_udm', 'udm_id');
		}
		
		function loadFromUdmCode($udmCode) {
			
			$myQuery = "
				SELECT udm_id
				FROM fh_udm
				WHERE udm_code = '" . DMDatabase::escape($udmCode) . "'
			";
			
			$udmId = DMDatabase::loadResult($myQuery);
			
			if ($udmId) {
				return self::load($udmId);
			} else {
				return false;
			}
			
		}
		
		function delete() {
		
			$myQuery = "
				DELETE FROM fh_r_udm_article
				WHERE udm_id = " . (int) $this->udm_id . "
			";
			
			DMDatabase::query($myQuery);
			
			return parent::delete();
		
		}
	}

?>