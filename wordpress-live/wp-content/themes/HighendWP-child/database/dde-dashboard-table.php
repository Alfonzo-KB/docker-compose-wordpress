
<?php 
include_once "edit-modal/edit-modal.php"; 
?>
<script><?php include_once $_SERVER['DOCUMENT_ROOT']."/kimberComponents/loadingGraphic/LoadingGraphic.js"; ?></script>
<script><?php include_once "js/dde-dashboard.js"; ?></script>
<script><?php include_once "js/licenses-remaining.js"; ?></script>
<script><?php include_once "js/revoked-table.js"; ?></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style><?php include_once "style/dde-dashboard.css"; ?></style>

<input id="show-edit-modal" type="radio" style="display: none"></input>
<input id="selected-row" type="text" style="display: none"></input>
<input id="query-type" type="text" style="display: none"></input>

<div class="table-header">Licenses Platform</div>
<input id="child-product-id" type="textbox" value="98294" style="display: none;" readonly></input>
<div id="licenses-assigned-table">
	<div id="lic-table-left-side">
		<div id="product-preview">
			<p class="preview-title">SELECTED LICENSE</p>
			<img id="selected-product-image"></img>
		</div>
		<div class="dde-product-list">
		</div>
	</div>
	<div>
		<table id="dde-licenses-table">
			<div id="populated-license"></div>
			<div class="unassigned-row">
				<button class="btn-orange" id="assign-button">ASSIGN NEW</button>
				<span class="unassigned-title"><b>UNASSIGNED LICENSES:</b></span>
				<span class="unassigned-number">0</span>
			</div>
		</table>
	</div>
</div>

<script>initAssignedTable();</script>

<div id="revoked"></div>

<script>
	var revokedTable = new RevokedTable();
	document.getElementById("revoked").append(revokedTable.returnTable());
	revokedTable.getRevokedLicenses();
</script>