<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Helpers
 */
class Iconic_WLV_Helpers {
	/**
	 * Get attribute taxonomies.
	 *
	 * @param null|array $sort
	 *
	 * @return array
	 */
	public static function get_attribute_taxonomies( $sort = null ) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( is_null( $sort ) || empty( $attribute_taxonomies ) ) {
			return $attribute_taxonomies;
		}

		$return = array();

		foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
			$key = sprintf( 'pa_%s', $attribute_taxonomy->attribute_name );
			$return[ $key ] = $attribute_taxonomy->attribute_label;
		}

		$return = array_merge( array_flip( $sort ), $return );

		return $return;
	}

	/**
	 * Clean and unique array.
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function get_clean_and_unique_array( $array ) {
		return array_unique( array_filter( $array ) );
	}
}