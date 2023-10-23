<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WLV_Shortcodes.
 *
 * @class    Iconic_WLV_Shortcodes
 * @version  1.0.0
 * @category Class
 * @author   Iconic
 */
class Iconic_WLV_Shortcodes {
	/**
	 * Run.
	 */
	public static function run() {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		add_shortcode( 'iconic_wlv_links', array( __CLASS__, 'links' ) );
	}

	/**
	 * Display product links.
	 */
	public static function links() {
		ob_start();
		Iconic_WLV_Product::output_linked_variations();

		return ob_get_clean();
	}
}