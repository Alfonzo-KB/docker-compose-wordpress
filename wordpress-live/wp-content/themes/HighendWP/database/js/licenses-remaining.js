function getLicensesRemaining(productId){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 10,
			'product_id': productId,
		},
		success: function(response){
			initRemainingSpan(response);
			clearEventListeners(document.getElementById("assign-button"));
			if(response > 0){
				initAssignButton();
			}
			loadingGraphic.hide();
		}
	});
}
function initAssignButton(){
	document.getElementById("assign-button").addEventListener("click", showAssignModal);
}

function clearEventListeners(element){
	var newElement = element.cloneNode(true);
	element.parentNode.replaceChild(newElement, element);
}

function initRemainingSpan(value){
	$(".unassigned-number").html(value);
}