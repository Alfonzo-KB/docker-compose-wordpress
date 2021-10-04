<?php

add_action('woocommerce_add_to_cart_validation', 'KDVT1021popup', 10, 3);
function KDVT1021popup($passed, $product_id, $quantity){
	//set this value to be for whatever product we need the popup for.
	if($product_id === 0){
		include_once $_SERVER['DOCUMENT_ROOT']."/kimberComponents/kimberModal/phpIncludes.php";
		?><script><?php include_once $_SERVER['DOCUMENT_ROOT']."/kimberComponents/kimberModal/KimberModal.js"; ?></script><?php

		?><script>
			window.addEventListener('load', (event) => {
				var title = "COME JOIN US!";
				var html = "<img style='margin:auto;' src='https://kimberbell.com/wp-content/uploads/2021/09/Popup-SignUp-Graphic-HIWTHI-Sew-Along.jpg' alt='Halloween Event Announcement'></img>";
				kimberModal = new KimberModal(title, html);
				kimberModal.altTheme();
				kimberModal.addToBody();
				kimberModal.show();
			});
		</script><?php
	}
	return $passed;
}
?>