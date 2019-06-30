<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableArrival extends DMTable { 
	
		var $arrival_id = null; 
		var $arrival_code = null; 
		var $arrival_code_str = null; 
		var $arrival_date = null; 
		var $vector_name = null; 
		var $subject = null;
		var $notes = null; 		
		var $arrival_archived = null;
		
		function __construct()
		{
			parent::__construct( 'fh_arrival', 'arrival_id');
		}
		
		function generateCodeStr() {
			
			$year = DMFormat::formatDate($this->arrival_date, 'Y', 'Y-m-d');
			$this->arrival_code_str = $year . '/' . str_pad($this->arrival_code, 4, '0', STR_PAD_LEFT);
			
		}
		
		function generateCode() {
		
			$year = DMFormat::formatDate($this->arrival_date, 'Y', 'Y-m-d');
			$myQuery = "
				SELECT MAX(arrival_code)
				FROM fh_arrival
				WHERE arrival_date >= '$year-01-01'
				AND arrival_date <= '$year-12-31'
			";
			
			$this->arrival_code = DMDatabase::loadResult($myQuery) + 1;
			$this->generateCodeStr();
		
		}
		
		
			// Elimina i riferimenti alle righe da tutte le tabelle collegate
		
		function clearRows() {
			
			//Cancello tutte le righe 
			$myQuery = "
				DELETE FROM fh_arrival_row
				WHERE arrival_id = " . (int) $this->arrival_id . "
			";
			
			DMDatabase::query($myQuery);
			
		}
	}

?>