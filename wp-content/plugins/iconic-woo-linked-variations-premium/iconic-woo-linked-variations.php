<?php
/**
 * Plugin Name: WooCommerce Linked Variations by Iconic
 * Plugin URI: https://iconicwp.com
 * Description: The new way to handle product variations.
 * Version: 1.2.0
 * Author: Iconic <info@iconicwp.com>
 * Author URI: https://iconicwp.com
 * Text Domain: iconic-wlv
 * WC requires at least: 2.6.14
 * WC tested up to: 5.8.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

class Iconic_Woo_Linked_Variations {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public static $version = '1.2.0';

	/**
	 * Class prefix
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var string $class_prefix
	 */
	protected $class_prefix = "Iconic_WLV_";

	/**
	 * Construct
	 */
	public function __construct() {
		$this->textdomain();
		$this->define_constants();
		$this->load_classes();
		$this->install();
	}

	/**
	 * Load textdomain
	 */
	public function textdomain() {
		load_plugin_textdomain( 'iconic-wlv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set constants
	 */
	public function define_constants() {
		$this->define( 'ICONIC_WLV_VERSION', '1.2.0' );
		$this->define( 'ICONIC_WLV_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'ICONIC_WLV_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'ICONIC_WLV_INC_PATH', ICONIC_WLV_PATH . 'inc/' );
		$this->define( 'ICONIC_WLV_VENDOR_PATH', ICONIC_WLV_INC_PATH . 'vendor/' );
		$this->define( 'ICONIC_WLV_BASENAME', plugin_basename( __FILE__ ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name
	 * @param string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load classes
	 */
	private function load_classes() {
		require_once( ICONIC_WLV_INC_PATH . 'class-core-autoloader.php' );

		Iconic_WLV_Core_Autoloader::run( array(
			'prefix'   => 'Iconic_WLV_',
			'inc_path' => ICONIC_WLV_INC_PATH,
		) );

		Iconic_WLV_Core_Licence::run( array(
			'basename' => ICONIC_WLV_BASENAME,
			'urls'     => array(
				'product'  => 'https://iconicwp.com/products/woocommerce-linked-variations/',
				'settings' => admin_url( 'admin.php?page=iconic-wlv-settings' ),
				'account'  => admin_url( 'admin.php?page=iconic-wlv-settings-account' ),
			),
			'paths'    => array(
				'inc'    => ICONIC_WLV_INC_PATH,
				'plugin' => ICONIC_WLV_PATH,
				'file'   => __FILE__,
			),
			'freemius' => array(
				'id'         => '1641',
				'slug'       => 'iconic-woo-linked-variations',
				'public_key' => 'pk_87add52278df025bc244015c9ef2a',
				'menu'       => array(
					'slug' => 'iconic-wlv-settings',
				),
			),
		) );

		Iconic_WLV_Core_Settings::run( array(
			'vendor_path'   => ICONIC_WLV_VENDOR_PATH,
			'title'         => 'WooCommerce Linked Variations',
			'version'       => self::$version,
			'menu_title'    => 'Linked Variations',
			'settings_path' => ICONIC_WLV_INC_PATH . 'admin/settings.php',
			'option_group'  => 'iconic_wlv',
			'docs'          => array(
				'collection'      => '/collection/172-woocommerce-linked-variations',
				'troubleshooting' => '/collection/172-woocommerce-linked-variations',
				'getting-started' => '/category/175-getting-started',
			),
			'cross_sells'   => array(
				'iconic-woothumbs',
				'iconic-woo-attribute-swatches',
			),
		) );

		Iconic_WLV_Settings::run();

		if ( ! Iconic_WLV_Core_Licence::has_valid_licence() ) {
			return;
		}

		if ( ! Iconic_WLV_Core_Helpers::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		Iconic_WLV_Assets::run();
		Iconic_WLV_Post_Types::run();
		Iconic_WLV_Product::run();
		Iconic_WLV_Compat_Import::run();
		Iconic_WLV_Compat_WPML::run();
		Iconic_WLV_Shortcodes::run();
		Iconic_WLV_Compat_Woo_Attribute_Swatches::run();

		add_action( 'admin_init', array( 'Iconic_WLV_Export', 'export' ) );
	}

	/**
	 * Install plugin.
	 */
	private function install() {
		add_action( 'plugins_loaded', array( 'Iconic_WLV_Database', 'install' ) );
	}
}

$iconic_wlv = new Iconic_Woo_Linked_Variations();