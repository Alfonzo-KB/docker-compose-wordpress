<?php
if ( class_exists( 'WXR_Importer' ) ) {

	/**
	 * Extend the WXR_Importer class to handle custom HB Content
	 * 
	 * @since 3.4.1
	 */
	class HB_WXR_Importer extends WXR_Importer {

		/**
		 * Primary class constructor.
		 *
		 * @since 3.4.1
		 */
		public function __construct( $options = array() ) {
			parent::__construct( $options );
		}
	}

} // class_exists( 'WXR_Importer' )
