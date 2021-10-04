function RevokedTable(){

	this.tableWrapper = document.createElement("DIV");
	this.tableHeader = document.createElement("DIV");
	this.tableHeader.setAttribute("class", "table-header");
	var tableTitle = document.createElement("h3");
	tableTitle.innerHTML = "Revoked Licenses";
	var dropBtn = document.createElement("span");
	// dropBtn.setAttribute("class", "dropdown-btn");
	// dropBtn.innerHTML = '<i class="fa fa-refresh" style="font-size:24px"></i>';
	// dropBtn.addEventListener("click", this.refreshTable);
	this.tableHeader.append(tableTitle);
	// this.tableHeader.append(dropBtn);
	this.tableWrapper.append(this.tableHeader);
	var revokedTable = document.createElement("table");
	revokedTable.setAttribute("id", "licenses-revoked-table");
	this.tableWrapper.append(revokedTable);

	this.returnTable = function(){
		return this.tableWrapper;
	}

	function populateTable(data){
		loadingGraphic.show();
		var columns = ["Product Name", "Purchase Date", "Customer", "Email"];
		var colVal = ["product_name", "purchase_date", "customer_nickname", "email"];
		var tableHeadRow = document.createElement("tr");
		for(var j = 0; j < columns.length; j++){
			var newHeadCell = document.createElement("th");
			newHeadCell.innerHTML = columns[j];
			tableHeadRow.append(newHeadCell);
		}
		revokedTable.append(tableHeadRow);

		for(var i = 0; i < data.length; i++){
			var newRow = document.createElement("tr");
			for(var j = 0; j < columns.length; j++){
				var newCell = document.createElement("td");
				newCell.innerHTML = data[i][colVal[j]];
				newRow.append(newCell);
			}
			revokedTable.append(newRow);
		}
	}

	function clearTable(){
		$("#licenses-revoked-table").empty();
	}

	this.getRevokedLicenses = function(){
		$.ajax({
			url: '/DDE/crud.php',
			type: 'POST',
			data: {
				'query': "6",
			},
			success: function(json){
				response = JSON.parse(json);
				populateTable(response);
				loadingGraphic.hide();
			}
		});
	}
}