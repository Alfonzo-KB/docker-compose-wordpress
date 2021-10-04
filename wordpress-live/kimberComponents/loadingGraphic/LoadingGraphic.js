function LoadingGraphic(){

	this.modal = document.createElement("DIV");
	this.modal.setAttribute("class", "hidden");

	this.returnModal = function(){
		this.modal.setAttribute("id", "load-Modal");
		document.body.append(this.modal);
		$("#load-Modal").load("/kimberComponents/loadingGraphic/loadingGraphic.html");
		this.modal.remove();
		return this.modal;
	}

	this.appendToBody = function(){
		this.modal.setAttribute("id", "load-Modal");
		document.body.append(this.modal);
		$("#load-Modal").load("/kimberComponents/loadingGraphic/loadingGraphic.html");
	}
	
	this.show = function(){
		this.modal.classList.remove("hidden");
	}

	this.hide = function(){
		this.modal.classList.add("hidden");
	}
}

