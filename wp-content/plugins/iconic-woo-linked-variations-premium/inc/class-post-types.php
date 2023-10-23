<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Setup post types.
 */
class Iconic_WLV_Post_Types {
	/**
	 * Run class.
	 */
	public static function run() {
		add_action( 'init', array( __CLASS__, 'add_post_types' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta' ) );
		add_action( 'delete_post', array( __CLASS__, 'delete_meta' ) );
		add_filter( 'manage_cpt_iconic_wlv_posts_columns', array( __CLASS__, 'admin_columns' ), 1000 );
		add_action( 'manage_cpt_iconic_wlv_posts_custom_column', array( __CLASS__, 'admin_columns_content' ), 10, 2 );
	}

	/**
	 * Add post types.
	 */
	public static function add_post_types() {
		self::add( array(
			'plural'              => __( 'Linked Variations', 'iconic-wlv' ),
			'singular'            => __( 'Linked Variations', 'iconic-wlv' ),
			'menu_name'           => __( 'Linked Variations', 'iconic-wlv' ),
			'key'                 => 'cpt_iconic_wlv',
			'has_archive'         => false,
			'public'              => false,
			'exclude_from_search' => true,
			'supports'            => array( 'title' ),
			'show_in_menu'        => 'edit.php?post_type=product',
			'publicly_queryable'  => false,
		) );
	}

	/**
	 * Method: Add
	 *
	 * @since 1.0.0
	 *
	 * @param array $options
	 */
	public static function add( $options ) {
		$defaults = array(
			"plural"              => "",                   // !required
			"singular"            => "",                   // !required
			"key"                 => false,                // !required
			"rewrite_slug"        => false,                // !recommended if has frontend visibility
			"rewrite_with_front"  => false,
			"rewrite_feeds"       => true,
			"rewrite_pages"       => true,
			"menu_icon"           => "dashicons-admin-post",
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post',
			'menu_name'           => false,
		);

		$options = wp_parse_args( $options, $defaults );

		if ( $options['key'] ) {
			$labels = array(
				'name'               => $options['plural'],
				'singular_name'      => $options['singular'],
				'add_new'            => _x( 'Add New', 'iconic-advanced-layered-nav' ),
				'add_new_item'       => _x( sprintf( 'Add New %s', $options['singular'] ), 'iconic-advanced-layered-nav' ),
				'edit_item'          => _x( sprintf( 'Edit %s', $options['singular'] ), 'iconic-advanced-layered-nav' ),
				'new_item'           => _x( sprintf( 'New %s', $options['singular'] ), 'iconic-advanced-layered-nav' ),
				'view_item'          => _x( sprintf( 'View %s', $options['singular'] ), 'iconic-advanced-layered-nav' ),
				'search_items'       => _x( sprintf( 'Search %s', $options['plural'] ), 'iconic-advanced-layered-nav' ),
				'not_found'          => _x( sprintf( 'No %s found', strtolower( $options['plural'] ) ), 'iconic-advanced-layered-nav' ),
				'not_found_in_trash' => _x( sprintf( 'No %s found in Trash', strtolower( $options['plural'] ) ), 'iconic-advanced-layered-nav' ),
				'parent_item_colon'  => _x( sprintf( 'Parent %s:', $options['singular'] ), 'iconic-advanced-layered-nav' ),
				'menu_name'          => $options['menu_name'] ? $options['menu_name'] : $options['plural'],
			);

			$args = array(
				'labels'              => $labels,
				'hierarchical'        => $options['hierarchical'],
				'supports'            => $options['supports'],
				'public'              => $options['public'],
				'show_ui'             => $options['show_ui'],
				'show_in_menu'        => $options['show_in_menu'],
				'menu_icon'           => $options['menu_icon'],
				'show_in_nav_menus'   => $options['show_in_nav_menus'],
				'publicly_queryable'  => $options['publicly_queryable'],
				'exclude_from_search' => $options['exclude_from_search'],
				'has_archive'         => $options['has_archive'],
				'query_var'           => $options['query_var'],
				'can_export'          => $options['can_export'],
				'capability_type'     => $options['capability_type'],
				'rewrite'             => false,
			);

			if ( $options['rewrite_slug'] ) {
				$args['rewrite'] = array(
					"slug"       => $options['rewrite_slug'],
					"with_front" => $options['rewrite_with_front'],
					"feeds"      => $options['rewrite_feeds'],
					"pages"      => $options['rewrite_pages'],
				);
			}

			register_post_type( $options['key'], $args );
		}
	}

	/**
	 * Modify admin columns.
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function admin_columns( $columns ) {
		$columns = array(
			'cb'        => $columns['cb'],
			'title'     => $columns['title'],
			'products'  => __( 'Products', 'iconic-wlv' ),
			'linked_by' => __( 'Linked by (attributes)', 'iconic-wlv' ),
			'date'      => $columns['date'],
		);

		return $columns;
	}

	/**
	 * Add custom column content.
	 *
	 * @param string $column
	 * @param int    $post_id
	 */
	public static function admin_columns_content( $column, $post_id ) {
		if ( in_array( $column, array( 'cb', 'title', 'author', 'date' ) ) ) {
			return;
		}

		$linked_variations_group = new Iconic_WLV_Linked_Variations_Group( $post_id );

		if ( $column === 'products' ) {
			$product_ids = $linked_variations_group->get_product_ids();

			if ( empty( $product_ids ) ) {
				echo '&mdash;';

				return;
			}

			$products = array();

			foreach ( $product_ids as $product_id ) {
				$title = get_the_title( $product_id );
				$link  = admin_url( 'post.php?post=' . $product_id . '&action=edit' );

				$products[] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_attr( $link ), $title );
			}

			echo implode( ', ', $products );

			unset( $products );

			return;
		} elseif ( $column === 'linked_by' ) {
			$attribute_slugs = $linked_variations_group->get_attributes();

			if ( empty( $attribute_slugs ) ) {
				echo '&mdash;';

				return;
			}

			$attributes = array();

			foreach ( $attribute_slugs as $attribute_slug ) {
				$taxonomy = get_taxonomy( $attribute_slug );

				if ( ! $taxonomy ) {
					continue;
				}

				$link = admin_url( 'edit-tags.php?taxonomy=' . $attribute_slug . '&post_type=product' );

				$attributes[] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_attr( $link ), $taxonomy->labels->singular_name );
			}

			echo implode( ', ', $attributes );

			unset( $attributes );

			return;
		}
	}

	/**
	 * Add meta boxes.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'iconic-wlv-linked-variations-meta',
			esc_html__( 'Linked Variations', 'iconic-wlv' ),
			array( __CLASS__, 'linked_variations_meta_box' ),
			'cpt_iconic_wlv',
			'normal',
			'default'
		);
	}

	/**
	 * Linked variations meta box.
	 */
	public static function linked_variations_meta_box() {
		global $post;

		if ( ! $post ) {
			return;
		}

		$linked_variations_group = new Iconic_WLV_Linked_Variations_Group( $post );
		$product_ids             = $linked_variations_group->get_product_ids();
		$attributes              = $linked_variations_group->get_attributes();
		$is_show_image           = $linked_variations_group->is_show_image();
		$style                   = $linked_variations_group->get_style();
		$attribute_taxonomies    = Iconic_WLV_Helpers::get_attribute_taxonomies( $attributes );
		?>

		<div class="panel woocommerce_options_panel">
			<div class="options_group">
				<p class="form-field">
					<label for="iconic-wlv-product-ids"><?php _e( 'Products', 'iconic-wlv' ); ?></label>
					<select class="wc-product-search" multiple="multiple" style="width: 85%;" id="iconic-wlv-product-ids" name="iconic_wlv_product_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products">
						<?php
						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
							}
						}
						?>
					</select>
					<?php echo wc_help_tip( __( 'Select which products should be linked together by the selected attributes.', 'iconic-wlv' ) ); ?>
				</p>

				<div class="form-field">
					<label for="iconic-wlv-attributes"><?php _e( 'Linked by (attributes)', 'iconic-wlv' ); ?></label>
					<?php if ( ! empty( $attribute_taxonomies ) ) { ?>
						<ul id="iconic-wlv-attributes" class="iconic-wlv-attributes">
							<?php foreach ( $attribute_taxonomies as $attribute_name => $attribute_label ) { ?>
								<?php
								$id      = sprintf( 'iconic-wlv-attributes__%s', $attribute_name );
								$checked = in_array( $attribute_name, $attributes );
								?>
								<li class="iconic-wlv-attributes__attribute">
									<input id="<?php echo esc_attr( $id ); ?>" type="checkbox" name="iconic_wlv_attributes[]" value="<?php echo esc_attr( $attribute_name ); ?>" <?php checked( $checked ); ?>>
									<label for="<?php echo esc_attr( $id ); ?>"><?php echo $attribute_label; ?></label>
									<i class="iconic-wlv-attributes__attribute-handle dashicons dashicons-menu" aria-hidden="true"></i>
								</li>
							<?php } ?>
						</ul>
					<?php } else { ?>
						<?php printf( __( 'You need to add some <a href="%s">product attributes</a> first.', 'iconic-wlv' ), admin_url( 'edit.php?post_type=product&page=product_attributes' ) ); ?>
					<?php } ?>
				</div>

				<p class="form-field">
					<label for="iconic-wlv-show-images"><?php _e( 'Show images?', 'iconic-wlv' ); ?></label>
					<input type="checkbox" id="iconic-wlv-show-images" name="iconic_wlv_show_images" <?php checked( $is_show_image ); ?>>
					<?php echo wc_help_tip( __( 'When checked, the options for the first selected attribute will be displayed as product images.', 'iconic-wlv' ) ); ?>
				</p>

				<p class="form-field">
					<label for="iconic-wlv-style"><?php _e( 'Style', 'iconic-wlv' ); ?></label>
					<select name="iconic_wlv_style" id="iconic-wlv-style">
						<option value="buttons" <?php selected( $style, 'buttons' ); ?>><?php _e( 'Buttons', 'iconic-wlv' ); ?></option>
						<option value="dropdown" <?php selected( $style, 'dropdown' ); ?>><?php _e( 'Dropdown', 'iconic-wlv' ); ?></option>
						<?php do_action( 'iconic_wlv_meta_box_style_options', $style ); ?>
					</select>
					<?php echo wc_help_tip( __( 'How should the attribute options be displayed?', 'iconic-wlv' ) ); ?>
				</p>
			</div>
		</div>

		<?php
	}

	/**
	 * Save meta.
	 *
	 * @param int $post_id
	 */
	public static function save_meta( $post_id ) {
		if ( ! isset( $_POST['post_type'] ) || $_POST['post_type'] !== 'cpt_iconic_wlv' || ( ! empty( $_POST['action'] ) && $_POST['action'] === 'inline-save' ) ) {
			return;
		}

		$product_ids = isset( $_POST['iconic_wlv_product_ids'] ) ? array_map( 'sanitize_text_field', $_POST['iconic_wlv_product_ids'] ) : array();
		$attributes  = isset( $_POST['iconic_wlv_attributes'] ) ? array_map( 'sanitize_text_field', $_POST['iconic_wlv_attributes'] ) : array();
		$show_image  = filter_input( INPUT_POST, 'iconic_wlv_show_images' ) === 'on';
		$style       = filter_input( INPUT_POST, 'iconic_wlv_style', FILTER_SANITIZE_STRING, array(
			'options' => array(
				'default' => 'buttons',
			),
		) );

		$args = array(
			'product_ids' => array_map( 'strval', $product_ids ),
			'attributes'  => $attributes,
			'show_image'  => $show_image,
			'style'       => $style,
		);

		Iconic_WLV_Database::update_linked_variations_meta( $post_id, $args );
	}

	/**
	 * Delete meta.
	 *
	 * @param int $post_id
	 */
	public static function delete_meta( $post_id ) {
		Iconic_WLV_Database::delete_linked_variations_meta( $post_id );
	}
}