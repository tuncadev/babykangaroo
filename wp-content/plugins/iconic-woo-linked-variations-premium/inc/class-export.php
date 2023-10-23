<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Export
 */
class Iconic_WLV_Export {
	/**
	 * Collect data into csv and export.
	 */
	public static function export() {
		// Exit early if user isn't admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Verify our iconic-wlv-export nonce.
		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), 'iconic-wlv-export' ) ) {
			return;
		}

		// Fetch headers.
		self::headers( sprintf( 'linked-variations-export-%s.csv', gmdate( 'Y-m-d' ) ) );

		// Fetch export data.
		self::output( self::data()['rows'], self::data()['labels'] );
	}

	/**
	 * Get CSV headers
	 *
	 * @param string $filename filename with date added.
	 */
	public static function headers( $filename ) {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Description: Linked Variations Export' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
	}

	/**
	 * Construct CSV data
	 */
	public static function data() {
		global $wpdb;

		$rows = $wpdb->get_results(
			"SELECT * FROM {$wpdb->prefix}iconic_woo_linked_variations"
		);

		if ( ! $rows ) {
			return;
		}

		$data = array();

		$labels = array(
			'id',
			'product_ids',
			'attributes',
			'show_image',
			'post_id',
			'style',
		);

		foreach ( $rows as $row ) {
			$data[] = array(
				'id'          => $row->id,
				'product_ids' => implode( ', ', unserialize( $row->product_ids ) ),
				'attributes'  => implode( ', ', unserialize( $row->attributes ) ),
				'show_image'  => $row->show_image,
				'post_id'     => $row->post_id,
				'style'       => $row->style,
			);
		}

		return array(
			'rows'   => $data,
			'labels' => $labels,
		);
	}

	/**
	 * Output formatted data to our csv
	 *
	 * @param array $rows   rows to add to csv.
	 * @param array $labels labels to make up csv header.
	 */
	public static function output( $rows, $labels ) {
		// Don't do anything if labels isn't an array.
		if ( ! is_array( $labels ) ) {
			return;
		}

		// Don't do anything if rows isn't an array.
		if ( ! is_array( $rows ) ) {
			return;
		}

		$file = fopen( 'php://output', 'w' );

		// Header row.
		fputcsv( $file, $labels );

		foreach ( $rows as $row ) {
			fputcsv( $file, $row );
		}

		fclose( $file );
		die();
	}
}
