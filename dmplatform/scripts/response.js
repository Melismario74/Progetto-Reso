/**
	Utility di gestione della risposta
		
	@package DMJavascript
	@subpackage DMResponse
			
*/
var DMResponse = {

	/**
		Parsa il json di risposta verificando il codice
		
		@param string la risposta del server
		@param string il campo del result da restituire se il json è valido
		@return un oggetto data se il result è > 0, altrimenti false
	**/
	parseJson: function (jsonData, dataElement) {
		
		if (dataElement == undefined) {
			dataElement = 'data';
		}
		
		try {
			obj = JSON && JSON.parse(jsonData) || $.parseJSON(jsonData);
		} catch (e) { 
			return false;
		}
		
		if (obj.result > 0) {
			return obj[dataElement];
		} else {
			return false;
		}
		
	},
	
	/**
		Verifica che la risposta sia un json valido
		
		@param string la risposta del server
		@return boolean false se non è valido, altrimenti l'oggetto parsato
	**/
	validateJson: function (jsonData) {
	
		try {
			obj = JSON && JSON.parse(jsonData) || $.parseJSON(jsonData);
		} catch (e) { 
			return false;
		}
		
		return obj;
		
	}
	
};