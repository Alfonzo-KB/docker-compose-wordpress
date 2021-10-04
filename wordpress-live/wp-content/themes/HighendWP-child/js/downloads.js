function populateDownloadTable(response, downloadTable){
	for(rowData of response) {
		var tableCols = ["product_name", "downloads_remaining", "access_expires"];
		var tableRow = document.createElement("tr");
		tableRow.setAttribute("name", rowData[tableCols["product_name"]]);

		var productImage = document.createElement("img");
		productImage.setAttribute("src", "/" );
		tableRow.append(productImage);

		for(var i = 0 ; i < tableCols.length; i++){
			var tableCell = document.createElement("td");
			tableCell.innerHTML = rowData[tableCols[i]];
			tableRow.append(tableCell);
		}
		var downloadBtn = document.createElement("a");
		downloadBtn.setAttribute("class", "btn");
		downloadBtn.setAttribute("href", rowData['download_url']);
		downloadBtn.innerHTML = "DOWNLOAD";
		tableRow.append(downloadBtn);
		downloadTable.append(tableRow);
	}
}

function DownloadOrganize(){

	var downloadWrapper = document.createElement("div");

	var downloadSorter = document.createElement("select");
	downloadSorter.setAttribute("name", "sort");

	var recentOption = document.createElement("option");
	recentOption.setAttribute("value", "recent");
	recentOption.innerHTML = "Recent";
	recentOption.addEventListener("click", sortRecent);
	downloadSorter.append(recentOption);

	var alphaOption = document.createElement("option");
	alphaOption.setAttribute("value", "alpha");
	alphaOption.innerHTML = "A-Z";
	alphaOption.addEventListener("click", sortAtoZ);
	downloadSorter.append(alphaOption);

	downloadWrapper.append(downloadSorter);

	var downloadTable = document.createElement("table");
	downloadWrapper.append(downloadTable);

	this.searchParameter = "";
	this.order_by = "ASC";

	this.getDownloads = function(){
		$.ajax({
			url: '/kimberComponents/advDownloads/crud.php',
			type: 'POST',
			data: {
				'query': "1",
				'order_by': this.order_by,
			},
			success: function(json){
				response = JSON.parse(json);
				populateDownloadTable(response, downloadTable);
				return response;
			}
		});
	}
	function sortAtoZ(){
		console.log("A-Z");
	}
	this.sortZtoA = function(){

	}
	function sortRecent(){
		console.log("sortRecent");
	}
	this.sortOldest = function(){

	}
	this.sortBy = function(searchParameter){

	}
	
	this.returnList = function(){
		return downloadWrapper;
	}
}