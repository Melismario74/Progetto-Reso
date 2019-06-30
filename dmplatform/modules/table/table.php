<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		Classe di wrapper per JTable
		
		@package DMTable
		@author DM Digital SRL		
	*/
	class DMTable extends JTable {
	
		var $_isUpdate = false;
		var $_errors = array();

		/**
			Ottiene un'instanza della table richiesta
			
			@param string il nome della tabella da utilizzare
			@param string il campo da utilizzare come id
			@return object
		**/
		public static function getInstance($tableName, $forcedPath = '', $db = null) {
		
			$className = 'DMTable' . $tableName;
			
			if ((!class_exists($className)) || ($forcedPath != '')) {
				if ($forcedPath != '') {
					$myPath = $forcedPath;
				} else {
					$myPath = DM_APP_PATH . DS . 'tables' . DS . strtolower($tableName) . '.php';
				}
				
				if (!file_exists($myPath)) {
					throw new DMTableException("Table file $myPath doesn't exist", -200);
				} else {
					require_once($myPath);
				}				
			}
			
			$myClass = new $className($tableName, null, $db); 
			
			return $myClass;
			
		}
			
		/**
			Costruisce l'istanza
			
			@param string il nome della tabella da utilizzare
			@param string il campo da utilizzare come id
			@return object
		**/
		function __construct($tableName, $id, $db = null) {
		
			if ($db == null) {
				$db =& DMDatabase::getDBO();
			}
			
			parent::__construct($tableName, $id, $db);
			
		}
		
		/**
			Carica una riga
			
			@param variant il valore da utilizzare come id
			@return boolean il risultato del caricamento
		**/
		public function load($id) {
		
			if (parent::load($id)) {
				$this->_isUpdate = true;
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
			Salva una riga
			
			@param boolean aggiorna i campi anche se sono null
			@return boolean il risultato del caricamento
		**/
		public function store($updateNulls = false) {
		
			$k = $this->_tbl_key;
 		
    	    if ($this->_isUpdate){
    	    	$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
    	    } else {
    	        $ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
    	    }
    	    
    	    if (!$ret) {
    	        $this->setError(get_class( $this ) . '::store failed - ' . $this->_db->getErrorMsg());
    	        return false;
    	    } else {
    	    	$this->_isUpdate = true;
    	        return true;
    	    }
    	    
		}
		
		/**
			Forza l'insert al prossimo store
		**/
		function forceInsert() {
		
			$this->_isUpdate = false;
			
		}
		
		/**
			Ritorna l'oggetto sotto forma di array
			
			@return array l'array contenente i campi dell'oggetto
		**/
		function asArray() {
		
			$myArray = array();
			
			foreach (get_object_vars($this) as $column => $value) {
				if (substr($column, 0, 1) != '_') {
					$myArray[$column] = $value;
				}			
			}
			
			return $myArray;
			
		}
		
	}
	
	/**
		TableException
		
		@package DMTable
		@author DM Digital SRL		
	*/
	class DMTableException extends DMException {
	
		public $backtrace;
		
		public function __construct($message = false, $code = false) {
		
			if (!$message) {
				$message = "Generic table error";
			}
			if (!$code) {
				$code = -100;
			}
		
			parent::__construct($message, $code);
		}
	}
?>