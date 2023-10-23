<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Import compatibility.
 *
 * Should work with most if the data is imported as meta.
 *
 * @class    Iconic_WLV_Compat_Import
 * @version  1.0.0
 * @category Class
 * @author   Iconic
 */
class Iconic_WLV_Compat_Import {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'save_post', array( __CLASS__, 'check_meta' ), 10, 3 );
	}

	/**
	 * Check post meta and replace with custom DB.
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 */
	public static function check_meta( $post_id, $post, $update ) {
		$post_type = get_post_type( $post_id );

		if ( 'cpt_iconic_wlv' !== $post_type ) {
			return;
		}

		$meta_keys = array(
			'iconic_wlv_product_skus',
			'iconic_wlv_product_ids',
			'iconic_wlv_attributes',
			'iconic_wlv_show_image',
			'iconic_wlv_style',
		);

		foreach ( $meta_keys as $key ) {
			$value = get_post_meta( $post_id, $key, true );

			if ( ! $value ) {
				continue;
			}

			$meta = self::format_meta( $key, $value );
			Iconic_WLV_Database::update_linked_variations_meta( $post_id, $meta );

			delete_post_meta( $post_id, $key );
		}
	}

	/**
	 * Format meta value for saving.
	 *
	 * @param string $meta_key
	 * @param string $meta_value
	 *
	 * @return mixed
	 */
	public static function format_meta( $meta_key, $meta_value ) {
		$meta_key = str_replace( 'iconic_wlv_', '', $meta_key );

		switch ( $meta_key ) {
			case 'product_skus':
			case 'product_ids':
			case 'attributes':
				$meta_value = explode( ',', $meta_value );
				break;
		}

		if ( is_array( $meta_value ) ) {
			$meta_value = Iconic_WLV_Helpers::get_clean_and_unique_array( $meta_value );
		}

		if ( $meta_key === 'product_skus' ) {
			$meta_key   = 'product_ids';
			$meta_value = self::get_ids_from_skus( $meta_value );
		}

		if ( $meta_key === 'product_ids' ) {
			$meta_value = array_map( 'strval', $meta_value );

			foreach ( $meta_value as $index => $id ) {
				if ( false === get_post_status( $id ) ) {
					unset( $meta_value[ $index ] );
				}
			}
		}

		return array( $meta_key => $meta_value );
	}

	/**
	 * Get IDs from SKUs.
	 *
	 * @param array $meta_value
	 *
	 * @return array
	 */
	public static function get_ids_from_skus( $meta_value ) {
		if ( empty( $meta_value ) ) {
			return $meta_value;
		}

		foreach ( $meta_value as $index => $sku ) {
			$id = wc_get_product_id_by_sku( $sku );

			if ( ! $id ) {
				unset( $meta_value[ $index ] );
				continue;
			}

			$meta_value[ $index ] = $id;
		}

		return $meta_value;
	}
}