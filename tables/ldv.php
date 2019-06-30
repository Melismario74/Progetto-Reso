<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableLdv extends DMTable
	{ 
		var $ldv_id = null; 
		var $ldv_code = null;
		var $ldv_code_str = null;
		var $ldv_date = null; 
		var $sender = null; 
		var $notes = null; 
		var $carton = null; 
		var $pallet = null; 
		
		function __construct()
		{
			parent::__construct( 'fh_ldv', 'ldv_id');
		}
		
		
		
		function delete() {
			
			return parent::delete();
			
		}
		
		function generateCodeStr() {
			
			$year = DMFormat::formatDate($this->ldv_date, 'Y', 'Y-m-d');
			$this->ldv_code_str = $year . '/fels' . str_pad($this->ldv_code, 4, '0', STR_PAD_LEFT);
			
		}
		
		function generateCodeStr1() {
			
			$year = DMFormat::formatDate($this->ldv_date, 'Y', 'Y-m-d');
			$this->ldv_code_str = $year . '/' . $this->ldv_code;
			
		}
		
		function generateCode() {
		
			$year = DMFormat::formatDate($this->ldv_date, 'Y', 'Y-m-d');
			$myQuery = "
				SELECT MAX(ldv_id)
				FROM fh_ldv
				WHERE ldv_date >= '$year-01-01'
				AND ldv_date <= '$year-12-31'
			";
			
			$this->ldv_code = DMDatabase::loadResult($myQuery) + 1;
			$this->generateCodeStr();
		
		}
		
			
		function clearRows() {
			
			//Cancello tutte le righe 
			$myQuery = "
				DELETE FROM fh_ldv_row
				WHERE ldv_id = " . (int) $this->ldv_id . "
			";
			
			DMDatabase::query($myQuery);
		
		}
	}

?>