var popupUser_data = {};

function popupUser_editUser(userId) {
	
	jQuery.post(
		'index.php?controller=user&task=jsonLoadUser&type=json',
		{
			"userId": userId,
			"getGroups": 1
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				popupUser_data.user = result.user;
				
				jQuery('#popupUser_editUsername').val(popupUser_data.user.username);
				jQuery('#popupUser_editName').val(popupUser_data.user.name);
				
				var targetElement = '#popupUser_tab_groups table tbody';
				
				jQuery(targetElement).html("");
				
				var groupsCount = popupUser_data.user.availableGroups.length;
				for (var i = 0; i < groupsCount; i++) {
					
					var groupData = popupUser_data.user.availableGroups[i];
					var isChecked = ''; 
					if (popupUser_data.user.groups.indexOf(groupData.group_id) > -1) {
						isChecked = 'checked="checked"';
					}
					
					var rowHtml = 
						'<tr>' +
							'<td><input type="checkbox" class="popupUser_editGroup" name="editGroup_' + groupData.group_id + '" value="' + groupData.group_id + '" ' + isChecked + '/></td>' +
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

function popupUser_newUser() {

	jQuery.post(
		'index.php?controller=user&task=jsonGetGroups&type=json',
		{
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				var targetElement = '#popupUser_tab_groups table tbody';
				
				jQuery(targetElement).html("");
				
				popupUser_data.user = {};
				popupUser_data.user.user_id = -1;
				popupUser_data.groups = result.groups;
				
				var groupsCount = popupUser_data.groups.length;
				for (var i = 0; i < groupsCount; i++) {
					
					var groupData = popupUser_data.groups[i];
					
					var rowHtml = 
						'<tr>' +
							'<td><input type="checkbox" class="popupUser_editGroup" name="editGroup_' + groupData.group_id + '" value="' + groupData.group_id + '" /></td>' +
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

function popupUser_save() {
	
	//Mini check
	if (jQuery('#popupUser_editName').val() == '') {
		alert("Occorre indicare il nome");
		return false;
	}
	if (jQuery('#popupUser_editUsername').val() == '') {
		alert("Occorre indicare il nome utente");
		return false;
	}
	if ((jQuery('#popupUser_editPassword').val() == '') && (popupUser_data.user.user_id == -1)) {
		alert("Occorre inserire una password");
		return false;
	}
	
	var postData = {
		"userId": popupUser_data.user.user_id,
		"name": jQuery('#popupUser_editName').val(),
		"username": jQuery('#popupUser_editUsername').val(),
		"password": jQuery('#popupUser_editPassword').val()	
	};
	
	jQuery('.popupUser_editGroup').each(function () {
		if (jQuery(this).is(":checked")) {
			postData['group_' + jQuery(this).val()] = 1;
		}
	});
	
	jQuery.post(
		'index.php?controller=user&task=jsonSave&type=json',
		postData,
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
						
				alert('Utente salvato');
				DMPopup.successPopup('popupUser');
		        jQuery('#popupUser').modal("hide");
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}