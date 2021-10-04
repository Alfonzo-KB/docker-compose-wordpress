<div id="dde-modal-backdrop" class="hidden">
	<div id="dde-edit-modal">
		<div class="modal-top">
			<h3 id="edit-modal-title">Edit Assigned License</h3>
			<a class="hide-modal-x">X</a>
		</div>
		<div class="modal-content">
			<form onsubmit="editLicense">
				<input id="assign-or-edit" type="checkbox" style="display: none;"></input>
				<div class="form-line">
					<label for="nickname">Customer</label>
					<input id="nickname" type="text"></input>
				</div>
				<div class="form-line">
					<label for="cus-email">Email</label>
					<input id="cus-email" type="text"></input>
				</div>
				<div class="form-line" id="edit-modal-messages"></div>
				<div class="modal-bottom-buttons" id="edit-buttons">
					<a class="admin-only" id="delete-edit-modal">DELETE</a>
					<a class="btn" id="clear-edit-modal">Clear</a>
					<a class="btn" id="resend-edit-modal">Resend Email</a>
					<a class="btn" id="save-modal-changes">Save Changes</a>
				</div>
				<div class="modal-bottom-buttons" id="assign-buttons">
					<a class="btn-orange" id="assign-license">Assign</a>
				</div>
			</form>
		</div>
	</div>
</div>
<style><?php include_once("edit-modal.css"); ?></style>
<script><?php include_once("edit-modal.js"); ?></script>