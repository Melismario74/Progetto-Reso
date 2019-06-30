/**
	Utility di supporto per print
		
	@package DMJavascript
	@subpackage DMPrint
		
*/

var DMPrint = {

	view: '',
	paperOrientation: undefined,
	output: 'pdf',
	template: 'default',
	title: 'print',	

	/**
		Inizializza DMPrint
	**/
	getInstance: function(params) {
	
		var myPrint = DMPrint;
	
		if (params.view != undefined) {
			myPrint.view = params.view;
		} else {
			throw new Error("DMPrint.view must be defined");
		}
		
		if (params.paperOrientation != undefined) {
			myPrint.paperOrientation = params.paperOrientation;
		}
		
		if (params.output != undefined) {
			myPrint.output = params.output;
		}
		
		if (params.template != undefined) {
			myPrint.template = params.template;
		}
		
		if (params.title != undefined) {
			myPrint.title = params.title;
		}
		
		return myPrint;
		
	},

	/**
		Esegue la stampa
		
		@param object i parametri da passare
		@param function la funzione di callback
	**/
	execPrint: function (params, callBackFunction) {
	
		jQuery.post(
			'index.php?mode=print&view=' + this.view + '&paperOrientation=' + this.paperOrientation + '&output=' + this.output + '&template=' + this.template + '&title=' + escape(this.title), 
			params,
			function(data) { 
				
				var result = DMResponse.validateJson(data);
			
				if (result != false) {	
					if (result.result >= 0) {
						if (result.print_url != undefined) {
							window.open(result.print_url, '_blank');
						}
						
						alert('Stampa eseguita');
						
						callBackFunction(result);
					} else {
						alert("Si è verificato un errore (" + result.result + "): " + result.description);
					}
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}	
		);
	
	}
	
}