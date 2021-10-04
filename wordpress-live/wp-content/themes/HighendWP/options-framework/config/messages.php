<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'hbthemes'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'hbthemes'),
		'numeric'      => __('Value needs to be Numeric', 'hbthemes'),
		'email'        => __('Value needs to be Valid Email', 'hbthemes'),
		'url'          => __('Value needs to be Valid URL', 'hbthemes'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'hbthemes'),
		'minlength'    => __('Length needs to be more than {0} characters', 'hbthemes'),
		'maxselected'  => __('Select no more than {0} items', 'hbthemes'),
		'minselected'  => __('Select at least {0} items', 'hbthemes'),
		'required'     => __('This is required', 'hbthemes'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'hbthemes'),
		'import_failed'     => __('Import failed', 'hbthemes'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'hbthemes'),
		'export_failed'     => __('Export failed', 'hbthemes'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'hbthemes'),
		'restore_nochanges' => __('Options identical to default', 'hbthemes'),
		'restore_failed'    => __('Restoration failed', 'hbthemes'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'hbthemes'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'hbthemes'),
	),

);

/**
 * EOF
 */