<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	class DMPrintRecondition extends DMPrintClass {
	
		function getInput() {
		}
		
		function execPrint($view, $template) {
		
			require_once(DM_APP_PATH . DS . 'helpers' . DS . 'invoicehelper.php');			
			$this->invoice = FHInvoiceHelper::loadInvoice($this->invoiceId);
			
			/**
			foreach ($this->row as $row) {
				$row->article = DMTable::getInstance('Article');
				$row->article->load($row->article_id);
			}
			**/
			
			return parent::execPrint($view, $template);
		
		}
		
	}