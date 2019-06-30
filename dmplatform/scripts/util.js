/**
	Utility varie
		
	@package DMJavascript
	@subpackage DMUtil
	@author DM Digital SRL		
*/
var DMUtil = {

	/**
		Fa il merge di due oggetti
		
		@param object il primo oggetto
		@param object il secondo oggetto
		@return object il merge
	**/
	mergeObjects: function (obj1, obj2) {
		
		var obj3 = {};
		
    	for (var attrname in obj1) { 
    		obj3[attrname] = obj1[attrname];
    	}
    	for (var attrname in obj2) { 
    		obj3[attrname] = obj2[attrname]; 
    	}
    	
    	return obj3;
		
	},
	
	/**
		Trasforma gli input che si trovano nella sourceParent in un array con i rispettivi valori
		
		@param string l'elemento da cui attingere
		@return array l'array degli input con i rispettivi valori
	**/
	inputsToArray: function(sourceParent) {
		
		var myResult = new Array();
		var myKey;
		var myValue;
	
		//input text
		jQuery(sourceParent + ' input[type="text"]').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
		});
		
		//password text
		jQuery(sourceParent + ' input[type="password"]').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
		});
		
		//checkbox
		jQuery(sourceParent + ' input[type="checkbox"]').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			if (jQuery(this).attr('checked')) {
				myResult.push(myKey + '=' + escape(myValue));
			}
			
		});
		
		//select
		jQuery(sourceParent + ' select').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
			
		});
		
		//textarea
		jQuery(sourceParent + ' textarea').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
			
		});
		
		//radio
		jQuery(sourceParent + ' input[type="radio"]:checked').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name'); 
			} else {
				myKey = jQuery(this).attr('id');
			} 
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
		});
		
		//hidden
		jQuery(sourceParent + ' input[type="hidden"]').each(function(index) {
			if (jQuery(this).attr('name') != undefined) {
				myKey = jQuery(this).attr('name');
			} else {
				myKey = jQuery(this).attr('id');
			}
			myValue = jQuery(this).val();
			
			myResult.push(myKey + '=' + escape(myValue));
			
		});
		
		return myResult;
		
	}
	
};