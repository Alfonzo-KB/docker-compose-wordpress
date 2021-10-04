function filterDownloads(){
	var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("downloads-search-bar");
    filter = input.value.toUpperCase();
    ul = document.getElementById("download-table");
    li = ul.getElementsByClassName("download-row");
    for (i = 0; i < li.length; i++) {
        a = li[i];
        txtValue = a.getAttribute("name");
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function populateDownloadTable(response, downloadTable){
	var tableHeadValues = ["Image", "Product", "Downloads Remaining", "Download"];
	var tableHead = document.createElement("tr");
	for (var i = 0; i < tableHeadValues.length; i++){
		var newTH = document.createElement("th");
		newTH.innerHTML = tableHeadValues[i];
		tableHead.append(newTH);
	}
	downloadTable.append(tableHead);

	for(rowData of response) {
		var tableCols = ["product_name", "downloads_remaining"];
		var tableRow = document.createElement("tr");
		tableRow.setAttribute("class", "download-row");
		tableRow.setAttribute("name", rowData["product_name"]);
		tableRow.setAttribute("order", rowData["order_id"]);

		var imageCell = document.createElement('td');
		imageCell.setAttribute("style", "width:50px; height:50px; margin:auto;" );
		var imageAnchor = document.createElement("a");
		imageAnchor.setAttribute("href", rowData["product_url"]);
		var productImage = document.createElement("img");
		productImage.setAttribute("src", rowData['image_src'] );
		productImage.setAttribute("style", "width:50px; height:50px; margin:auto;" );
		imageAnchor.append(productImage);
		imageCell.append(imageAnchor);
		tableRow.append(imageCell);

		for(var i = 0 ; i < tableCols.length; i++){
			var tableCell = document.createElement("td");
			if(i == 0){
				var anchor = document.createElement("a");
				anchor.setAttribute("href", rowData["product_url"]);
				anchor.innerHTML = rowData[tableCols[i]];
				tableCell.append(anchor);
			}else{
				tableCell.innerHTML = rowData[tableCols[i]];
			}
			tableRow.append(tableCell);
		}
		var newCell = document.createElement("td");
		var downloadBtn = document.createElement("a");
		downloadBtn.setAttribute("class", "btn");
		downloadBtn.setAttribute("href", rowData['download_url']);
		downloadBtn.innerHTML = "DOWNLOAD";
		newCell.append(downloadBtn);
		tableRow.append(newCell);
		downloadTable.append(tableRow);
	}
}

function DownloadOrganize(){

	var downloadWrapper = document.createElement("div");

	var searchBar = document.createElement("input");
	searchBar.setAttribute("type", "text");
	searchBar.setAttribute("onkeyup", "filterDownloads()");
	searchBar.setAttribute("placeholder", "Search my downloads..");
	searchBar.setAttribute("id", "downloads-search-bar");
	downloadWrapper.append(searchBar);

	var downloadSorter = document.createElement("select");
	downloadSorter.setAttribute("id", "download-filter");
	downloadSorter.setAttribute("name", "sort");
	downloadSorter.addEventListener("change", reSort);

	var optionsTitle = ["Latest", "Oldest", "A-Z", "Z-A"];
	var optionsName = ["recent", "oldest", "alpha", "ahpla"];
	for(var i = 0; i < optionsName.length; i++){
		var newOption = document.createElement("option");
		newOption.setAttribute("value", optionsName[i]);
		newOption.innerHTML = optionsTitle[i];
		downloadSorter.append(newOption);
	}

	downloadWrapper.append(downloadSorter);

	var downloadTable = document.createElement("table");
	downloadTable.setAttribute("id", "download-table");
	downloadWrapper.append(downloadTable);

	this.getDownloads = function(){
		$.ajax({
			url: '/kimberComponents/advDownloads/crud.php',
			type: 'POST',
			data: {
				'query': 1,
			},
			success: function(json){
				response = JSON.parse(json);
				populateDownloadTable(response, downloadTable);
				reSort();
			}
		});
	}
	function reSort(){
		switch(document.getElementById("download-filter").value){
			case "recent":
				sortRecent();
			break;
			case "alpha":
				sortAtoZ();
			break;
			case "oldest":
				sortOldest();
			break;
			case "ahpla":
				sortZtoA();
			break;
		}
	}
	function sortRecent(){
		let parentNode = document.getElementById("download-table");
		var e = parentNode.getElementsByClassName("download-row");
		[].slice
		  .call(e)
		  .sort(function(a, b) {
		  	return b.getAttribute("order").localeCompare(a.getAttribute("order"));
		  })
		  .forEach(function(val, index) {
		    parentNode.appendChild(val);
		  });
	}
	function sortAtoZ(){
		let parentNode = document.getElementById("download-table");
		var e = parentNode.getElementsByClassName("download-row");
		[].slice
		  .call(e)
		  .sort(function(a, b) {
		    return a.getAttribute("name").localeCompare(b.getAttribute("name"));
		  })
		  .forEach(function(val, index) {
		    parentNode.appendChild(val);
		  });
	}
	function sortOldest(){
		let parentNode = document.getElementById("download-table");
		var e = parentNode.getElementsByClassName("download-row");
		[].slice
		  .call(e)
		  .sort(function(a, b) {
		    return a.getAttribute("order").localeCompare(b.getAttribute("order"));
		  })
		  .forEach(function(val, index) {
		    parentNode.appendChild(val);
		  });
	}
	function sortZtoA(){
		let parentNode = document.getElementById("download-table");
		var e = parentNode.getElementsByClassName("download-row");
		[].slice
		  .call(e)
		  .sort(function(b, a) {
		    return a.getAttribute("name").localeCompare(b.getAttribute("name"));
		  })
		  .forEach(function(val, index) {
		    parentNode.appendChild(val);
		  });
	}
	
	this.returnList = function(){
		return downloadWrapper;
	}
}