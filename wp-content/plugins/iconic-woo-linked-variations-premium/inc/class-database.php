<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Database
 */
class Iconic_WLV_Database {
	/**
	 * Database version.
	 *
	 * @var string
	 */
	protected static $version = '1.0.2';

	/**
	 * Linked variations DB name.
	 *
	 * @var string
	 */
	public static $linked_variations_db_name = 'iconic_woo_linked_variations';

	/**
	 * Install/update the DB table.
	 */
	public static function install() {
		if ( version_compare( get_site_option( 'iconic_wlv_db_version' ), self::$version, '>=' ) ) {
			return;
		}

		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$table_name = $wpdb->prefix . self::$linked_variations_db_name;

		$sql = "CREATE TABLE $table_name (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`product_ids` longtext,
		`attributes` longtext,
		`show_image` tinyint(1) DEFAULT NULL,
		`post_id` bigint(20) DEFAULT NULL,
		`style` text,
		PRIMARY KEY (`id`)
		) $collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "iconic_wlv_db_version", self::$version );
	}

	/**
	 * Get meta for post.
	 *
	 * @return null|object|bool
	 */
	public static function get_linked_variations_meta( $post_id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::$linked_variations_db_name;

		$prepare = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE post_id = %d",
			$post_id
		);

		return $wpdb->get_row( $prepare );
	}

	/**
	 * Update linked variations meta.
	 *
	 * @param int   $post_id
	 * @param array $meta
	 */
	public static function update_linked_variations_meta( $post_id, $meta ) {
		global $wpdb;

		foreach ( $meta as $key => $value ) {
			$meta[ $key ] = maybe_serialize( $value );
		}

		$table_name = $wpdb->prefix . self::$linked_variations_db_name;

		$exists = self::get_linked_variations_meta( $post_id );

		if ( ! is_null( $exists ) ) {
			$wpdb->update(
				$table_name,
				$meta,
				array( 'post_id' => $post_id ),
				null,
				array( '%d' )
			);
		} else {
			$meta['post_id'] = $post_id;

			$wpdb->insert(
				$table_name,
				$meta
			);
		}
	}

	/**
	 * Delete linked variations meta.
	 *
	 * @param int $post_id
	 */
	public static function delete_linked_variations_meta( $post_id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . self::$linked_variations_db_name;

		$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
	}

	/**
	 * Get group ID containing product.
	 *
	 * @param int $product_id
	 *
	 * @return bool|int
	 */
	public static function get_linked_variations_group_id_for_product( $product_id ) {
		$product_id = apply_filters( 'iconic_wlv_product_id', $product_id );

		static $group_ids = array();

		if ( ! empty( $group_ids[ $product_id ] ) ) {
			return $group_ids[ $product_id ];
		}

		global $wpdb;

		$group_ids[ $product_id ] = false;

		$table_name = $wpdb->prefix . self::$linked_variations_db_name;

		$prepare = $wpdb->prepare(
			"SELECT wlv.* FROM $table_name AS wlv
			INNER JOIN {$wpdb->posts} AS p ON p.ID = wlv.post_id
			WHERE product_ids LIKE '%%\"%d\"%%'
			AND p.post_status = 'publish'",
			$product_id
		);

		$group = $wpdb->get_row( $prepare );

		if ( ! $group ) {
			return $group_ids[ $product_id ];
		}

		$group_id = absint( $group->post_id );
		$group_status = get_post_status( $group_id );

		if ( ! in_array( $group_status, array( 'publish', 'private', 'draft' ) ) ) {
			return $group_ids[ $product_id ];
		}

		$group_ids[ $product_id ] = $group_id;

		return $group_ids[ $product_id ];
	}
}
