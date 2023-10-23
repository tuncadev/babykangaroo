<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Settings
 */
class Iconic_WLV_Settings {
	/**
	 * Run.
	 */
	public static function run() {
		add_filter( 'wpsf_show_save_changes_button_iconic_wlv', '__return_false' );
		add_filter( 'wpsf_show_tab_links_iconic_wlv', '__return_false' );
	}

	/**
	 * Get export button.
	 *
	 * @return string
	 */
	public static function export_button() {
		return sprintf( '<a class="button button-secondary" href="%s">%s</a>', admin_url( 'admin.php?page=iconic-wlv-settings&action=download_csv&_wpnonce=' . wp_create_nonce( 'iconic-wlv-export' ) ), __( 'Export Data', 'iconic-wlv' ) );
	}
}
