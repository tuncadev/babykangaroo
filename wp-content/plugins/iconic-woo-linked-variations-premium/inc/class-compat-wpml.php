<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPML compatibility.
 *
 * @class          Iconic_WLV_Compat_WPML
 * @version        1.0.0
 * @category       Class
 * @author         Iconic
 */
class Iconic_WLV_Compat_WPML {
	/**
	 * Run.
	 */
	public static function run() {
		if ( ! Iconic_WLV_Core_Helpers::is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			return;
		}

		add_filter( 'iconic_wlv_product_id', array( __CLASS__, 'product_id' ), 10 );
		add_filter( 'iconic_wlv_linked_variation_id', array( __CLASS__, 'linked_variation_id' ), 10 );
		add_filter( 'iconic_wlv_group_attributes', array( __CLASS__, 'translate_attributes' ), 10, 2 );
		add_filter( 'iconic_wlv_variation_term_slug', array( __CLASS__, 'translate_variation_term_slug' ), 10, 2 );
	}

	/**
	 * Translate the variation attribute slug from the default language
	 * to the request language.
	 *
	 * @param string $term_slug
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	public static function translate_variation_term_slug( $term_slug, $taxonomy ) {
		$term            = get_term_by( 'slug', $term_slug, $taxonomy );
		$translated_term = self::translate_term( $term->term_id, $term->taxonomy );

		return $translated_term->slug;
	}

	/**
	 * Get original product ID.
	 *
	 * @param int $product_id
	 *
	 * @return int
	 */
	public static function product_id( $product_id ) {
		$args          = array(
			'element_id'   => $product_id,
			'element_type' => 'product',
		);
		$language_info = apply_filters( 'wpml_element_language_details', null, $args );

		if ( empty( $language_info ) ) {
			return $product_id;
		}

		$source_language_code = isset( $language_info->source_language_code ) ? $language_info->source_language_code : $language_info->language_code;

		return apply_filters( 'wpml_object_id', $product_id, 'product', true, $source_language_code );
	}

	/**
	 * Translate a term from the default site language to the request language
	 *
	 * @param int    $term_id
	 * @param string $taxonomy
	 *
	 * @return WP_Term
	 */
	public static function translate_term( $term_id, $taxonomy ) {
		$term               = get_term_by( 'id', intval( $term_id ), $taxonomy );
		$translated_term_id = apply_filters( 'wpml_object_id', $term->term_id, $term->taxonomy, true, null );
		$translated_term    = get_term_by( 'id', intval( $translated_term_id ), $term->taxonomy );

		return $translated_term;
	}

	/**
	 * Translate attributes for display.
	 * The internal logic uses the attributes from the default site language (i.e. english)
	 * to find the linked variations. But we need to translate to the request language
	 * during display/output context so we use the translated attributes data (labels, urls, etc.)
	 *
	 * @param array $attributes
	 * @param int   $product_id
	 *
	 * @return array
	 */
	public static function translate_attributes( $attributes, $product_id ) {
		if ( ! empty( $attributes['slugs'] ) ) {
			foreach ( $attributes['slugs'] as $taxonomy => $slug ) {
				$term                             = get_term_by( 'slug', $slug, $taxonomy );
				$translated_term_id               = apply_filters( 'wpml_object_id', $term->term_id, $term->taxonomy, true, null );
				$translated_term                  = get_term_by( 'id', intval( $translated_term_id ), $term->taxonomy );
				$attributes['slugs'][ $taxonomy ] = $translated_term->slug;
			}
		}

		return $attributes;
	}

	/**
	 * Get translated product ID.
	 *
	 * @param int $product_id
	 *
	 * @return int
	 */
	public static function linked_variation_id( $product_id ) {
		return apply_filters( 'wpml_object_id', $product_id, 'product', true, null );
	}
}
