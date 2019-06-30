<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableMovement extends DMTable
	{ 
		var $movement_id = null; 
		var $article_id = null;  
		var $stock_id = null;  
		var $batch_in_id = null;  
		var $batch_out_id = null;  
		var $source_id = null;  
		var $movement_type = null;  
		var $created_by = null;  
		var $created_date = null; 
		
		function __construct()
		{
			parent::__construct( 'fh_movement', 'movement_id');
		}
		
		function store() {
		
			if ($this->movement_id < 1) {
				if ($this->created_by == null) {
					$this->created_by = DMUser::getUserId();
				}
				if ($this->created_date == null) {
					$this->created_date = date('Y-m-d H:i:s');
				}
				
			}
			
			return parent::store();
			
		}
		
		function delete() {
		
			$myQuery = "
				SELECT movement_detail_id
				FROM fh_movement_detail
				WHERE movement_id = " . (int) $this->movement_id . "
			";
			
			$movementDetailIds = DMDatabase::loadResultArray($myQuery);
			foreach ($movementDetailIds as $movementDetailId) {
				$myMovementDetail = DMTable::getInstance('MovementDetail');
				$myMovementDetail->load($movementDetailId);
				
				if (!$myMovementDetail->delete()) {
					return false;
				}
				
				unset($myMovementDetail);
			}
			
			return parent::delete();
		
		}
	}

?>