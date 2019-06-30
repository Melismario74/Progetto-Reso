function fhUsers_search() {

	var tbodyElement = '#fhUsers_results_table tbody';
	var progressElement = '#fhUsers .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=user&task=jsonGetUsers&type=json',
		{
			
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
			
				if (result.result > 0) {
				
					users = result.users;
					usersCount = users.length;
					
					for (var i = 0; i < usersCount; i++) {
						var userData = users[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + userData.user_id + '</td>' +
								'<td>' +
									'<a href="#" onclick="fhUsers_openUser(' + userData.user_id + '); return false;">' + userData.name + '</a>' +
								'</td>' +
								'<td style="text-align: center">' +
									'<button class="btn btn-danger" onclick="fhUsers_deleteUser(' + userData.user_id + '); return false;"><i class="icon-white icon-remove"></i></button>' +
								'</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
					
				}
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhUsers_openUser(userId) {

	var params = 'userId=' + userId;
	
	var popupUser = DMPopup.getInstance({
		name: 'popupUser',
		includeCallback: function () {
			this.openPopup('open', params);
		}
	});

}

function fhUsers_deleteUser(userId) {
	
	if (confirm("Vuoi davvero eliminare l'utente?")) {
		jQuery.post(
			'index.php?controller=user&task=jsonDeleteUser&type=json',
			{
				"userId": userId
			},
			function (data) {
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
							
					alert("Utente eliminato");
					fhUsers_search();
									
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
			}
			
		);
	}
	
}

function fhUsers_new() {

	var popupUser = DMPopup.getInstance({
		name: 'popupUser',
		includeCallback: function () {
			this.openPopup('open', '');
		},
		onSuccess: function(data) {
			fhUsers_search();
		}
	});
	
}