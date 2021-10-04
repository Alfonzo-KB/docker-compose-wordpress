var dashboardTable = document.getElementById("dde-dashboard-table");
const loadingGraphic = new LoadingGraphic();
loadingGraphic.appendToBody();

function getAllLicenses(){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 0,
		},
		success: function(json){
			response = JSON.parse(json);
			clearTable();
			populateTable(response);
			return response;
		}
	});
}

function getAllDDEYears(){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 7,
		},
		success: function(json){
			response = JSON.parse(json);
			initLicTab(response);
			getAllDDEProducts();
		}
	});
}

function getAllDDEProducts(){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 9,
		},
		success: function(json){
			response = JSON.parse(json);
			initProductTab(response);
		}
	});
}

function getLicenses(productId){
	getProductImage(productId);
	getLicensesRemaining(productId);
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 1,
			'product_id': productId,
		},
		success: function(json){
			response = JSON.parse(json);
			clearTable();
			populateTable(response);
			return response;
		}
	});
}

function addShopLicense(shopId, productId, amount){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 11,
			'amount': amount,
			'shop_id': shopId,
			'product_id': productId,
		},
		success: function(json){
			location.reload();
		}
	});
}

function getMostRecentLicense(){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 2,
		},
		success: function(json){
			response = JSON.parse(json);
			$("#populated-license").html(response[0]["product_name"]);
			$("#child-product-id").val(response[0]["product_id"]);
			getLicenses(response[0]["product_id"]);
		}
	});
}

function getProductImage(productId){
	$.ajax({
		url: '/DDE/crud.php',
		type: 'POST',
		data: {
			'query': 12,
			'product_id': productId,
		},
		success: function(json){
			response = JSON.parse(json);
			document.getElementById("selected-product-image").setAttribute("src", response);
		}
	});
}

function initAssignedTable(){
	loadingGraphic.show();
	getAllDDEYears();
	getMostRecentLicense();
	var modal = document.getElementById("dde-modal-backdrop");
	document.body.append(modal);
	initAssignButton();
}

function initLicTab(data){
	loadingGraphic.show();
	var productList = document.getElementsByClassName("dde-product-list");
	for (var i = 0; i < data.length; i++){
		var nextProduct = document.createElement("div");
		nextProduct.setAttribute("class", "lic-tab "+data[i]["year"]+"-year");
		var nextYearTitle = document.createElement("div");
		nextYearTitle.setAttribute("class", "dropdown-title");
		nextYearTitle.innerHTML = data[i]["year"];
		nextYearTitle.addEventListener("click", showOrHideOptions);
		nextProduct.append(nextYearTitle);
		var nextYearDropdown = document.createElement("div");
		nextYearDropdown.setAttribute("class", "dd-container dd-year-container hidden");
		nextProduct.append(nextYearDropdown);
		productList[0].append(nextProduct);
	}
}

function initProductTab(data){
	loadingGraphic.show();
	var productList = document.getElementsByClassName("dde-product-list")[0];
	for (var i = 0; i < data.length; i++){
		var parentYear = productList.getElementsByClassName(data[i]["year"]+"-year")[0];
		var nextProduct = document.createElement("div");
		nextProduct.setAttribute("class", "dropdown-option");
		nextProduct.setAttribute("name", data[i]["child_id"]);
		nextProduct.innerHTML = data[i]["product_name"];
		nextProduct.addEventListener("click", changeTableInfo);
		parentYear.getElementsByClassName("dd-year-container")[0].append(nextProduct);
	}
	loadingGraphic.hide();
}

function showOrHideOptions(){
	this.classList.toggle('opened');
	this.parentElement.getElementsByClassName("dd-container")[0].classList.toggle('hidden');
}

function changeTableInfo(){
	loadingGraphic.show();
	newTitle = this.textContent;
	newProduct = this.getAttribute("name");
	$("#populated-license").html(newTitle);
	$("#child-product-id").val(newProduct);
	getLicenses(newProduct);
}

function populateTable(data){
	$(".license-row").remove();
	for(var i = 0; i < data.length; i++){
		newRow = createRow(data[i]);
		$("#dde-licenses-table").append(newRow);
	}
}

function clearTable(){
	$("#dde-licenses-table").empty();
	populateTableHead();
}

function populateTableHead(){
	var table = document.getElementById("dde-licenses-table");
	var headerRow = ["Assigned Date", "Customer", "Email", "Download Count", "Options"];
	for(var i = 0; i < headerRow.length; i++){
		var newBox = document.createElement("th");
		newBox.innerHTML =headerRow[i];
		table.append(newBox);
	}
}

function createRow(data){
	rowOrder = ["purchase_date","customer_nickname", "email", "download_count"];
	tableRow = document.createElement("tr");
	tableRow.setAttribute("order-id", data["order_id"]);
	tableRow.setAttribute("class", "license-row");

	for(var i = 0; i < rowOrder.length; i++){
		colName = rowOrder[i];
		newBox = createBox(data[colName]);
		newBox.setAttribute("class", colName);
		tableRow.append(newBox);
	}
	tableRow.append(createTableOptionButtons());
	return tableRow;
}

function createBox(info){
	tableBox = document.createElement("td");
	tableBox.innerHTML = info;
	return tableBox;
}

function createTableOptionButtons(){
	tableBox = document.createElement("td");
	tableBox.setAttribute("style", "text-align: center; margin: auto;");

	newButton = document.createElement("a");
	newButton.setAttribute("class", "btn-orange edit-button");
	newButton.addEventListener("click", showEditModal);
	newButton.innerHTML = "edit";
	tableBox.append(newButton);
	
	return tableBox;
}

function showEditModal(){
	$("#show-edit-modal").prop("checked", true);
	var selected = document.getElementById("selected-row");
	var selectedRow = this.parentElement.parentElement;
	var orderId = selectedRow.getAttribute("order-id");
	$("#selected-row").val(orderId);

	//fill-in modal's fields
	var modal = document.getElementById("dde-modal-backdrop");
	var licenseCustomer = selectedRow.getElementsByClassName("customer_nickname")[0].textContent;
	var licenseEmail = selectedRow.getElementsByClassName("email")[0].textContent;
	$("#edit-modal-title").text("Edit Assigned License");
	$("#nickname").val(licenseCustomer);
	$("#cus-email").val(licenseEmail);
	

	document.getElementById("assign-or-edit").checked = false;
	modal.classList.remove("hidden");
}

function showAssignModal(){
	$("#show-edit-modal").prop("checked", true);
	var licenseId = document.getElementById("child-product-id").value;

	//fill-in modal's fields
	$("#edit-modal-title").text("Assign License");
	$("#nickname").val("");
	$("#cus-email").val("");
	document.getElementById("assign-or-edit").checked = true;
	$("#dde-modal-backdrop").removeClass("hidden");
}

function handleAddLicense(){
	var shopId = parseInt($("#add-license-shop-id").val());
	var productId = parseInt($("#add-license-product-id").val());
	var amount = parseInt($("#add-license-amount").val());
	addShopLicense(shopId, productId, amount);
}