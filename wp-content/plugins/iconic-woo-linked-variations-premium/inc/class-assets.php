<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Assets
 */
class Iconic_WLV_Assets {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'current_screen', array( __CLASS__, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
	}

	/**
	 * Enqueue admin assets.
	 */
	public static function enqueue_admin_assets() {
		if ( ! is_admin() ) {
			return;
		}

		self::register_scripts();
		self::register_styles();

		global $current_screen;

		if ( ! $current_screen ) {
			return;
		}

		$action = isset( $_GET['action'] ) ? $_GET['action'] : $current_screen->action;

		if ( $current_screen->post_type !== 'cpt_iconic_wlv' || ! in_array( $action, array( 'add', 'edit' ) ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 100 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_styles' ), 100 );
	}

	/**
	 * Enqueue frontend assets.
	 */
	public static function enqueue_frontend_assets() {
		if ( is_admin() ) {
			return;
		}

		self::register_scripts();
		self::register_styles();
		self::frontend_scripts();
		self::frontend_styles();
	}

	/**
	 * Register admin scripts.
	 */
	private static function register_scripts() {
		$register_scripts = array(
			'iconic-wlv-admin' => array(
				'src'     => ICONIC_WLV_URL . 'assets/admin/js/main.min.js',
				'deps'    => array( 'jquery', 'jquery-ui-sortable' ),
				'version' => ICONIC_WLV_VERSION,
			),
			'iconic-wlv' => array(
				'src'     => ICONIC_WLV_URL . 'assets/frontend/js/main.min.js',
				'deps'    => array( 'jquery' ),
				'version' => ICONIC_WLV_VERSION,
			),
		);

		foreach ( $register_scripts as $name => $props ) {
			wp_register_script( $name, $props['src'], $props['deps'], $props['version'], true );
		}
	}

	/**
	 * Register all styles.
	 */
	private static function register_styles() {
		$register_styles = array(
			'iconic-wlv-admin' => array(
				'src'     => ICONIC_WLV_URL . 'assets/admin/css/main.css',
				'deps'    => array(),
				'version' => ICONIC_WLV_VERSION,
			),
			'iconic-wlv' => array(
				'src'     => ICONIC_WLV_URL . 'assets/frontend/css/main.css',
				'deps'    => array(),
				'version' => ICONIC_WLV_VERSION,
			),
		);

		foreach ( $register_styles as $name => $props ) {
			wp_register_style( $name, $props['src'], $props['deps'], $props['version'] );
		}
	}

	/**
	 * Load all admin scripts.
	 */
	public static function admin_scripts() {
		$enqueue = array(
			'jquery',
			'selectWoo',
			'wc-enhanced-select',
			'jquery-tiptip',
			'jquery-ui-sortable',
			'iconic-wlv-admin',
		);

		foreach ( $enqueue as $handle ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Load all frontend scripts.
	 */
	public static function frontend_scripts() {
		$enqueue = array(
			'jquery',
			'iconic-wlv',
		);

		foreach ( $enqueue as $handle ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Load all admin styles.
	 */
	public static function admin_styles() {
		$enqueue = array(
			'woocommerce_admin_styles',
			'iconic-wlv-admin',
		);

		foreach ( $enqueue as $handle ) {
			wp_enqueue_style( $handle );
		}
	}

	/**
	 * Load all frontend styles.
	 */
	public static function frontend_styles() {
		$enqueue = array(
			'iconic-wlv',
		);

		foreach ( $enqueue as $handle ) {
			wp_enqueue_style( $handle );
		}
	}
}