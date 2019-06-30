<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );

	$document =& DMDocument::getInstance();
	$document->addViewCSS('aggregation');
	$document->addViewJS('aggregation');
	$document->setTitle("Aggregazione");
	
	class AggregationView extends DMView {
	
		function display() {
			
			parent::display();
			
		}
		
	}

?>