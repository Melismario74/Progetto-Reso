<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableMovementDetail extends DMTable
	{ 
		var $movement_detail_id = null;
		var $movement_id = null; 
		var $stock_id = null;  
		var $chargelist_id = null;
		var $quantity_units = null;  
		var $quantity_packages = null;  
		var $udm_code = null;
		
		function __construct()
		{
			parent::__construct( 'fh_movement_detail', 'movement_detail_id');
		}
		
		function store() {
			
			$storeResult = parent::store();
			
			//Devo aggiornare la stock_cache
			$myMovement = DMTable::getInstance('Movement');
			$myMovement->load($this->movement_id);
			
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
			
			FHStockHelper::updateStockCache($myMovement->article_id, $this->stock_id);
			
			return $storeResult;
			
		}
		
		function delete() {
		
			$myMovement = DMTable::getInstance('Movement');
			$myMovement->load($this->movement_id);
				
			//Se il movimento è legato ad una chargelist, devo rimuovere la quantità dalla colonna opportuna
			if ($this->chargelist_id > 0) {			
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'chargelisthelper.php');

				FHChargelistHelper::updateChargelistArticle($this->chargelist_id, $myMovement->article_id, - $this->quantity_units, $this->stock_id);
			}
			
			$deleteResult = parent::delete();
			
			if ($deleteResult) {
				require_once(DM_APP_PATH . DS . 'helpers' . DS . 'stockhelper.php');
				FHStockHelper::updateStockCache($myMovement->article_id, $this->stock_id);
			}
			
			return $deleteResult;
			
		}
	}

?>