<?php

	// No direct access
	defined('_DMEXEC') or die( 'Restricted access' );
	
	class DMTableArticle extends DMTable
	{ 
		var $article_id = null; 
		var $name = null; 
		var $article_code = null; 
		var $ean_code = null; 
		var $description_lang_1 = null; 
		var $description_lang_2 = null; 
		var $description_lang_3 = null; 
		var $description_lang_4 = null; 
		var $package_code = null; 
		var $package_description = null; 
		var $package_units = null; 
		var $width = null; 
		var $height = null; 
		var $depth = null; 
		var $quality_batch_in_codes = null;
		var $quality_message = null;
		var $pallet_packages = null;
		
		function __construct()
		{
			parent::__construct( 'fh_article', 'article_id');
		}
		
		function loadFromArticleCode($articleCode) {
			
			$myQuery = "
				SELECT article_id
				FROM fh_article
				WHERE article_code = '" . DMDatabase::escape($articleCode) . "'
			";
			
			$articleId = DMDatabase::loadResult($myQuery);
			
			if ($articleId) {
				return self::load($articleId);
			} else {
				return false;
			}
			
		}
		
		function loadFromEanCode($eanCode) {
			
			$myQuery = "
				SELECT article_id
				FROM fh_article
				WHERE ean_code = '" . DMDatabase::escape($eanCode) . "'
			";
			
			$results = DMDatabase::loadResultArray($myQuery);
			if (count($results) != 1) {
				return false;
			}
			
			$articleId = $results[0];
			
			if ($articleId) {
				return self::load($articleId);
			} else {
				return false;
			}
			
		}
	}

?>