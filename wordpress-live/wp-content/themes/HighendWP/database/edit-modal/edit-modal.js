function updateCustomerLicense(nickname, email, orderId){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 3,
			'nickname': nickname,
			'cus_email': email,
			'order_id': orderId,
		},
		success: function(json){
			loadingGraphic.hide();
			location.reload();
		}
	});
}

function deleteLicense(orderId){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 5,
			'order_id': orderId,
		},
		success: function(json){
			loadingGraphic.hide();
			location.reload();
		},
		error: function(error){
			displayMessage("Selected License cannot be deleted!", false);
		}
	});
}

function assignLicenseCall(productId, nickname, cusEmail){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 4,
			'product_id': productId,
			'nickname': nickname,
			'cus_email': cusEmail,
		},
		success: function(json){
			loadingGraphic.hide();
			location.reload();
		}
	});
}

function sendEmail(email){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 11,
			'email': email,
		},
		success: function(json){
			var message = "Email has been sent to ".concat(email);
			displayMessage(message, true);
		}
	})
}

function initEditModal(){
	var exitButtons = document.getElementsByClassName("hide-modal-x");
	for (var i = 0; i < exitButtons.length; i++){
		exitButtons[i].addEventListener("click", hideEditModal);
	}
	var clearFieldsButton = document.getElementById("clear-edit-modal");
	clearFieldsButton.addEventListener("click", clearFields);
	document.getElementById("delete-edit-modal").addEventListener("click", handleDeleteLicense);
	document.getElementById("resend-edit-modal").addEventListener("click", resendEmail);
	document.getElementById("save-modal-changes").addEventListener("click", editLicense);
	document.getElementById("assign-license").addEventListener("click", assignLicense);
}

function displayMessage(text, success){
	var message = document.createElement("h4");
	const style = {true: "message-success", false: "message-failure"};
	message.setAttribute("class", style[success]);
	message.innerText = text;
	document.getElementById("edit-modal-messages").append(message);
}

function clearMessages(){
	var parent = document.getElementById("edit-modal-messages");
	while (parent.firstChild){
		parent.removeChild(parent.firstChild);
	}
}

function resendEmail(){
	loadingGraphic.show();
	var email = document.getElementById("cus-email").value;
	if (validateEmail(email)){
		clearMessages();
		sendEmail(email);
	}else{
		clearMessages();
		displayMessage("Please enter a valid email address", false);
	}
	loadingGraphic.hide();
}

function validateEmail(email){
	var re = /\S+@\S+\.\S+/;
  	return re.test(email);
}

function clearFields(){
	$("#nickname").val("");
	$("#cus-email").val("");
	clearMessages();
}

function editLicense(){
	loadingGraphic.show();
	hideEditModal();
	var customer = $("#nickname").val();
	var email = $("#cus-email").val();
	var orderId = $("#selected-row").val();
	updateCustomerLicense(customer, email, orderId);
}

function assignLicense(){
	loadingGraphic.show();
	var id = $("#child-product-id").val();
	var customer = $("#nickname").val();
	var email = $("#cus-email").val();
	hideEditModal();
	assignLicenseCall(id, customer, email);
}	

function hideEditModal(){
	$("#show-edit-modal").prop("checked", false);
	var modal = document.getElementById("dde-modal-backdrop");
	modal.setAttribute("class", "hidden");
	clearMessages();
}

function handleDeleteLicense(){
	loadingGraphic.show();
	hideEditModal();
	var id = $("#selected-row").val();
	deleteLicense(id);
}

initEditModal();