<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableDdt extends DMTable
	{ 
		var $ddt_id = null; 
		var $ddt_code = null; 
		var $ddt_code_str = null;		
		var $ddt_date = null; 
		var $cargo = null; 
		var $notes = null; 
		
		
		function __construct()
		{
			parent::__construct( 'fh_ddt', 'ddt_id');
		}
		function generateCodeStr() {
			
			$year = DMFormat::formatDate($this->ddt_date, 'Y', 'Y-m-d');
			$this->ddt_code_str = $year . '/esente' . str_pad($this->ddt_code, 4, '0', STR_PAD_LEFT);
			
		}
		
		function generateCodeStr1() {
			
			$year = DMFormat::formatDate($this->ddt_date, 'Y', 'Y-m-d');
			$this->ddt_code_str = $year . '/' . $this->ddt_code;
			
		}
		
		function generateCode() {
		
			$year = DMFormat::formatDate($this->ddt_date, 'Y', 'Y-m-d');
			$myQuery = "
				SELECT MAX(ddt_id)
				FROM fh_ddt
				WHERE ddt_date >= '$year-01-01'
				AND ddt_date <= '$year-12-31'
			";
			
			$this->ddt_code = DMDatabase::loadResult($myQuery) + 1;
			$this->generateCodeStr();
		
		}
	}

?>