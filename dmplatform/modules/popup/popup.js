/**
	Utility di supporto per popup
		
	@package DMJavascript
	@subpackage DMPopup
	@author DM Digital SRL		
*/

var DMPopup_onSuccess = new Array();
var DMPopup_onCancel = new Array();
var DMPopup_onDelete = new Array();

var DMPopup = {

	name: '',
	onSuccess: undefined,
	onCancel: undefined,
	onDelete: undefined,
	includeCallback: undefined,
	focus: undefined,
	opacity: 1,
	autoInclude: true,
	

	/**
		Inizializza un popup
	**/
	getInstance: function(params) {
	
		var myPopup = DMPopup;
	
		if (params.name != undefined) {
			myPopup.name = params.name;
		} else {
			throw new Error("DMPopup.name must be defined");
		}
		
		if (params.onSuccess != undefined) {
			myPopup.onSuccess = params.onSuccess;
		}
		
		if (params.onCancel != undefined) {
			myPopup.onCancel = params.onCancel;
		}
		
		if (params.onDelete != undefined) {
			myPopup.onDelete = params.onDelete;
		}
		
		if (params.includeCallback != undefined) {
			myPopup.includeCallback = params.includeCallback;
		}
		
		if (params.focus != undefined) {
			myPopup.focus = params.focus;
		}
		
		if (params.opacity != undefined) {
			myPopup.opacity = params.opacity;
		}
		
		if (params.autoInclude != undefined) {
			myPopup.autoInclude = params.autoInclude;
		}
		
		if (myPopup.autoInclude) {
			myPopup.includePopup(function () {
				console.log("getInstance include callback");
				if (myPopup.includeCallback != undefined) {
					myPopup.includeCallback();
				}
			});
		}
		
		return myPopup;
		
	},

	/**
		Include un popup
		
		@param function la funzione di callback
	**/
	includePopup: function (callBackFunction) {
	
		console.log("includePopup start");
		//se non esiste la div che accoglie i popup, la creo
		if (jQuery('#dmPopupContainer').length == 0) {
			jQuery('body').append('<div id="dmPopupContainer"></div>');
		}
	
		//se il popup non è già stato caricato, lo carico
		if (jQuery('#' + this.name).length == 0) {
			
			myRequest = 'index.php?mode=popup&task=include&popupName=' + this.name;
					
			jQuery.post(
				myRequest, 
				function(data) { 
					var includeData = JSON.parse(data);
					console.log("includePopup has arrived");
					jQuery('#dmPopupContainer').append(includeData.header);
					
					jQuery.ajax({
						url: includeData.script,
						dataType: "script",
						async: false
					});

					if (callBackFunction != undefined) {
						console.log("includePopup callback!");
						setTimeout(callBackFunction, 0);
					}
				}
			);
			
		} else {
			if (callBackFunction != undefined) {
				callBackFunction(); 
			}
		}
	
	},
	
	/**
		Apre un popup
		
		@param string l'azione da chiamare, di default open
		@param string i parametri da allegare alla richiesta
		@param function il callback in caso di successo
		@param function il callback in caso di annullamento
	**/
	openPopup: function (action, params, onSuccess, onCancel, onDelete) {
	
		console.log("openPopup start");
		popupName = this.name;
		popupOpacity = this.opacity;
		popupFocus = this.focus;
	
		myRequest = 'index.php?mode=popup&task=open&popupName=' + popupName;
		
		if (action == undefined) {
			action = 'open';
		}		
		
		if (params != undefined) {
			myRequest += '&' + params;
		}
		
		if (onSuccess != undefined) {
			this.onSuccess = onSuccess;
		}
		
		if (onCancel != undefined) {
			this.onCancel = onCancel;
		}
		
		if (onDelete != undefined) {
			this.onDelete = onDelete;
		}
		
		DMPopup_onSuccess[popupName] = this.onSuccess;
		DMPopup_onCancel[popupName] = this.onCancel;
		DMPopup_onDelete[popupName] = this.onDelete;
		
		jQuery('#' + popupName).html('<div class="progress progress-striped active" style="margin: 10px;"><div class="bar" style="width: 100%;"></div></div>');
		jQuery('#' + popupName).modal();
		
		jQuery.post(	
			myRequest, 
			{ 
				"action": action
			}, 
			function(data){
				jQuery('#' + popupName).html(data);
				if (popupOpacity != undefined) {
					jQuery('#' + popupName).parent().css('opacity', popupOpacity);
				}				
				
				if (popupFocus != undefined) {
					jQuery('#' + popupFocus).focus();
				}
			}
		);
		
	},
	
	successPopup: function (popupName, successValue) {
		if (DMPopup_onSuccess[popupName] != undefined) {
			callbackFunction = DMPopup_onSuccess[popupName];
			callbackFunction(successValue);
		}
	},
	
	cancelPopup: function (popupName) {
		if (DMPopup_onCancel[popupName] != undefined) {
			callbackFunction = DMPopup_onCancel[popupName];
			callbackFunction();
		}
	},
	
	deletePopup: function (popupName) {
		if (DMPopup_onDelete[popupName] != undefined) {
			callbackFunction = DMPopup_onDelete[popupName];
			callbackFunction();
		}
	}
	
}