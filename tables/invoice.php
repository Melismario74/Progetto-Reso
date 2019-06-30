<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableInvoice extends DMTable { 
	
		var $invoice_id = null; 
		var $invoice_code = null; 
		var $invoice_code_str = null; 
		var $invoice_date = null; 
		var $client_name = null; 
		var $notes = null; 
		var $subject = null;
		var $invoice_archived = null;
		
		function __construct()
		{
			parent::__construct( 'fh_invoice', 'invoice_id');
		}
		
		function generateCodeStr() {
			
			$year = DMFormat::formatDate($this->invoice_date, 'Y', 'Y-m-d');
			$this->invoice_code_str = $year . '/' . str_pad($this->invoice_code, 4, '0', STR_PAD_LEFT);
			
		}
		
		function generateCode() {
		
			$year = DMFormat::formatDate($this->invoice_date, 'Y', 'Y-m-d');
			$myQuery = "
				SELECT MAX(invoice_code)
				FROM fh_invoice
				WHERE invoice_date >= '$year-01-01'
				AND invoice_date <= '$year-12-31'
			";
			
			$this->invoice_code = DMDatabase::loadResult($myQuery) + 1;
			$this->generateCodeStr();
		
		}
		
		/**
			Elimina i riferimenti alle righe da tutte le tabelle collegate
		**/
		function clearRows() {
			
			//Cancello tutte le righe 
			$myQuery = "
				DELETE FROM fh_invoice_row
				WHERE invoice_id = " . (int) $this->invoice_id . "
			";
			
			DMDatabase::query($myQuery);
			
			//Cancello tutti i movimenti e relativi dettagli
			$myQuery = "
				SELECT movement_id
				FROM fh_movement
				WHERE movement_type = 'INVOICE'
				AND source_id = " . (int) $this->invoice_id . "
			";
			
			$movementIds = DMDatabase::loadResultArray($myQuery);
			foreach ($movementIds as $movementId) {
				$myMovement = DMTable::getInstance('Movement');
				$myMovement->load($movementId);
				$myMovement->delete();
				unset($myMovement);
			}
			
		}
	}

?>