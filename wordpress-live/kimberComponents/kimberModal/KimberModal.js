function KimberModal(title, html){
	this.title = title;
	this.html = html;

	this.modalWrapper = document.createElement("DIV");
	this.modalWrapper.setAttribute("class", "modal-backdrop hidden");
	this.modal = document.createElement("DIV");
	this.modal.setAttribute("class", "kimber-modal");

	var modalHeader = document.createElement("DIV");
	modalHeader.setAttribute("class", "modal-header");
	var modalTitle = document.createElement("h3");
	modalTitle.setAttribute("class", "modal-title");
	modalTitle.innerText = this.title;
	var exitModal = document.createElement("a");
	exitModal.setAttribute("class", "hide-modal");
	exitModal.addEventListener("click", hideAllModals);
	exitModal.innerText = "X";
	modalHeader.append(modalTitle);
	modalHeader.append(exitModal);

	var modalContent = document.createElement("DIV");
	modalContent.setAttribute("class", "modal-content");
	modalContent.innerHTML = this.html;

	this.modal.append(modalHeader);
	this.modal.append(modalContent);
	this.modalWrapper.append(this.modal);

	this.returnModal = function(){
		return this.modalWrapper;
	}

	this.addToBody = function(){
		document.getElementsByTagName("BODY")[0].appendChild(this.modalWrapper);
	}

	this.show = function(){
		this.modalWrapper.classList.remove("hidden");
	}

	this.hide = function(){
		this.modalWrapper.classList.add("hidden");
	}

	this.altTheme = function(){
		this.modalWrapper.classList.add("alt-theme");
	}

	function hideAllModals(){
		var modals = document.getElementsByClassName("modal-backdrop");
		for(var i = 0; i < modals.length; i++){
			modals[i].classList.add("hidden");
		}
	}
}