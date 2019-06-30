<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	/**
		DMHelper legacy
		
		@package DMLegacy
		@author DM Digital SRL		
	*/
	class DMHelper {
	
		function getPositiveValue($value, $default) {
			return DMFormat::positiveValue($value, $default);
		}
		
		function getStringValue($value, $default) {
			return DMFormat::stringValue($value, $default);
		}
		
		function addItemOnTop($array, $item) {
			return DMFormat::addItemOnTop($array, $item);
		}
		
		function formatCurrency2($input, $currencySym = '€', $useColors = false, $symbolBefore = false) {
			if (class_exists("DMConfHelper")) {
				$decimalSeparator = DMConfHelper::getValue('decimal_separator', '.');
				$thousandSeparator = DMConfHelper::getValue('thousand_separator', ',');
			} else {
				$decimalSeparator = '.';
				$thousandSeparator = ',';
			}
		
			return DMFormat::formatCurrency($input, $useColors, $currencySym, $symbolBefore, $decimalSeparator, $thousandSeparator);
		}
		
		function formatDate($date, $targetformat = 'd/m/Y', $sourceFormat = 'Y-m-d', $offset = 0, $targetLang = 'it') {
			return DMFormat::formatDate($date, $targetformat, $sourceFormat, $offset, $targetLang);
		}
	}
	
?>