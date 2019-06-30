<?php

		// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableOrder extends DMTable { 
	
		var $order_id = null; 
		var $order_code = null; 
		var $order_code_str = null; 
		var $order_date = null; 
		var $client_name = null; 
		var $notes = null; 
		var $subject = null;
		var $order_archived = null;
		
		function __construct()
		{
			parent::__construct( 'fh_order', 'order_id');
		}
		
		function generateCodeStr() {
			
			$year = DMFormat::formatDate($this->order_date, 'Y', 'Y-m-d');
			$this->order_code_str = $year . '/' . str_pad($this->order_code, 4, '0', STR_PAD_LEFT);
			
		}
		
		function generateCode() {
		
			$year = DMFormat::formatDate($this->order_date, 'Y', 'Y-m-d');
			$myQuery = "
				SELECT MAX(order_code)
				FROM fh_order
				WHERE order_date >= '$year-01-01'
				AND order_date <= '$year-12-31'
			";
			
			$this->order_code = DMDatabase::loadResult($myQuery) + 1;
			$this->generateCodeStr();
		
		}
		
		/**
			Elimina i riferimenti alle righe da tutte le tabelle collegate
		**/
		function clearRows() {
			
			//Cancello tutti gli impegni
			$myQuery = "
				SELECT order_row_id
				FROM fh_order_row
				WHERE order_id = " . (int) $this->order_id . "
				
			";
			
			$orderRowIds = DMDatabase::loadResultArray($myQuery);
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'logisticshelper.php');
			
			foreach ($orderRowIds as $orderRowId) {
				$myOrderRow = DMTable::getInstance('Orderrow');
				$myOrderRow->load($orderRowId);
				FHLogisticsHelper::addToUdmCommitted($myOrderRow->udm_id, $myOrderRow->article_id, $myOrderRow->stock_id, - $myOrderRow->quantity_units );
				unset($myOrderRow);
			}
			
			//Cancello tutte le righe 
			$myQuery = "
				DELETE FROM fh_order_row
				WHERE order_id = " . (int) $this->order_id . "
			";
			
			DMDatabase::query($myQuery);
			
		}
	}


?>