var popupVector_data = {};

function popupVector_editVector(vectorId) {
	
	jQuery.post(
		'index.php?controller=vector&task=jsonLoadVector&type=json',
		{
			"vectorId": vectorId,
			"getGroups": 1
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				popupVector_data.vector = result.vector;
				
				jQuery('#popupVector_editVectorname').val(popupVector_data.vector.vectorname);
				jQuery('#popupVector_editName').val(popupVector_data.vector.name);
				
				var targetElement = '#popupVector_tab_groups table tbody';
				
				jQuery(targetElement).html("");
				
				var groupsCount = popupVector_data.vector.availableGroups.length;
				for (var i = 0; i < groupsCount; i++) {
					
					var groupData = popupVector_data.vector.availableGroups[i];
					var isChecked = ''; 
					if (popupVector_data.vector.groups.indexOf(groupData.group_id) > -1) {
						isChecked = 'checked="checked"';
					}
					
					var rowHtml = 
						'<tr>' +
							'<td><input type="checkbox" class="popupVector_editGroup" name="editGroup_' + groupData.group_id + '" value="' + groupData.group_id + '" ' + isChecked + '/></td>' +
							'<td>' + groupData.name + '</td>' +
						'</tr>';
						
					jQuery(targetElement).append(rowHtml);
					
				}
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}


function popupVector_newVector() {

	jQuery.post(
		'index.php?controller=vector&task=jsonGetGroups&type=json',
		{
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				var targetElement = '#popupVector_tab_groups table tbody';
				
				jQuery(targetElement).html("");
				
				popupVector_data.vector = {};
				popupVector_data.vector.vector_id = -1;
				popupVector_data.groups = result.groups;
				
				var groupsCount = popupVector_data.groups.length;
				for (var i = 0; i < groupsCount; i++) {
					
					var groupData = popupVector_data.groups[i];
					
					var rowHtml = 
						'<tr>' +
							'<td><input type="checkbox" class="popupVector_editGroup" name="editGroup_' + groupData.group_id + '" value="' + groupData.group_id + '" /></td>' +
							'<td>' + groupData.name + '</td>' +
						'</tr>';
						
					jQuery(targetElement).append(rowHtml);
					
				}
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);

}

function popupVector_save() {
	
	//Mini check
	if (jQuery('#popupVector_editName').val() == '') {
		alert("Occorre indicare il nome");
		return false;
	}
	
	
	var postData = {
		"vectorId": popupVector_data.vector.vector_id,
		"name": jQuery('#popupVector_editName').val()
	};
	
	jQuery('.popupVector_editGroup').each(function () {
		if (jQuery(this).is(":checked")) {
			postData['group_' + jQuery(this).val()] = 1;
		}
	});
	
	jQuery.post(
		'index.php?controller=vector&task=jsonSave&type=json',
		postData,
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				alert('Utente salvato');
				DMPopup.successPopup('popupVector');
		        jQuery('#popupVector').modal("hide");
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}