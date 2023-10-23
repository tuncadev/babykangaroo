<?php
/**
 * Product Categories Widget
 *
 * @author   Automattic
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Martfury_Widget_Product_Categories' ) ) {
	/**
	 * Product categories widget class.
	 *
	 * @extends WC_Widget
	 */
	class Martfury_Widget_Product_Categories extends WC_Widget {

		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce mf_widget_product_categories';
			$this->widget_description = esc_html__( 'A list of product categories.', 'martfury' );
			$this->widget_id          = 'mf_product_categories';
			$this->widget_name        = esc_html__( 'Martfury - Product Categories', 'martfury' );
			$this->settings           = array(
				'title'                     => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Product categories', 'martfury' ),
					'label' => esc_html__( 'Title', 'martfury' ),
				),
				'orderby'                   => array(
					'type'    => 'select',
					'std'     => 'name',
					'label'   => esc_html__( 'Order by', 'martfury' ),
					'options' => array(
						'order' => esc_html__( 'Category order', 'martfury' ),
						'title' => esc_html__( 'Name', 'martfury' ),
					),
				),
				'count'                     => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show product counts', 'martfury' ),
				),
				'hide_empty'                => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Hide empty categories', 'martfury' ),
				),
				'show_all_cats'             => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Only show children of the current category', 'martfury' ),
					'class' => 'mf_categories_show_children_only'
				),
				'show_first_level_children' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Only show the first level children of the current category', 'martfury' ),
					'class' => 'mf_categories_show_children_only_els'
				),
				'max_depth'                 => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Maximum depth', 'martfury' ),
				),
				'all_cats_text'                 => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'All Categories Text', 'martfury' ),
				),
				'all_cats_link'                 => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'All Categories Link', 'martfury' ),
				),
				'show_view_more'            => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show View More', 'martfury' ),
					'class' => 'mf_categories_show_more'
				),
				'numbers'                   => array(
					'type'  => 'text',
					'std'   => '15',
					'label' => esc_html__( 'Categories per view', 'martfury' ),
					'class' => 'mf_categories_show_more_els'
				),
				'show_more'                 => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Show More', 'martfury' ),
					'label' => esc_html__( 'Show More Text', 'martfury' ),
					'class' => 'mf_categories_show_more_els'
				),
				'show_less'                 => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Show Less', 'martfury' ),
					'label' => esc_html__( 'Show Less Text', 'martfury' ),
					'class' => 'mf_categories_show_more_els'
				),
			);

			parent::__construct();
			add_action( 'admin_print_scripts', array( $this, 'admin_scripts' ) );
		}

		/**
		 * Enqueue scripts in the backend.
		 */
		public function admin_scripts() {
			global $pagenow;

			if ( 'widgets.php' != $pagenow && 'customize.php' != $pagenow ) {
				return;
			}

			wp_enqueue_script( 'martfury-widget-admin', MARTFURY_ADDONS_URL . '/assets/js/product-categories-admin.js', array(), '20201029' );

		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			global $wp_query, $post;

			$count                     = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
			$orderby                   = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
			$hide_empty                = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
			$show_children_only        = isset( $instance['show_all_cats'] ) ? $instance['show_all_cats'] : $this->settings['show_all_cats']['std'];
			$show_first_level_children = isset( $instance['show_first_level_children'] ) ? $instance['show_first_level_children'] : $this->settings['show_first_level_children']['std'];
			$show_view_more            = isset( $instance['show_view_more'] ) ? $instance['show_view_more'] : $this->settings['show_view_more']['std'];
			$numbers                   = isset( $instance['numbers'] ) ? $instance['numbers'] : $this->settings['numbers']['std'];
			$show_more                 = isset( $instance['show_more'] ) ? $instance['show_more'] : $this->settings['show_more']['std'];
			$show_less                 = isset( $instance['show_less'] ) ? $instance['show_less'] : $this->settings['show_less']['std'];

			$list_args = array(
				'show_count'   => $count,
				'hierarchical' => 1,
				'taxonomy'     => 'product_cat',
				'hide_empty'   => $hide_empty,
			);
			$max_depth = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['max_depth']['std'] );

			$list_args['menu_order'] = false;
			$list_args['depth']      = $max_depth;

			if ( 'order' === $orderby ) {
				$list_args['menu_order'] = 'asc';
			} else {
				$list_args['orderby'] = $orderby;
				if ( $orderby === 'count' ) {
					$list_args['order'] = 'desc';
				}
			}

			$this->current_cat   = false;
			$this->cat_ancestors = array();

			if ( is_tax( 'product_cat' ) ) {
				$this->current_cat   = $wp_query->queried_object;
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

			} elseif ( is_singular( 'product' ) ) {
				if ( apply_filters( 'martfury_yoast_get_primary_term_id', function_exists( 'yoast_get_primary_term_id' ) ) ) {
					if ( $primary_id = yoast_get_primary_term_id( 'product_cat' ) ) {
						$term                = get_term( $primary_id, 'product_cat' );
						$this->current_cat   = $term;
						$this->cat_ancestors = get_ancestors( $primary_id, 'product_cat' );
					}

				} else {
					$terms = wc_get_product_terms(
						$post->ID,
						'product_cat',
						apply_filters(
							'woocommerce_product_categories_widget_product_terms_args',
							array(
								'orderby' => 'parent',
								'order'   => 'DESC',
							)
						)
					);

					if ( $terms ) {
						$main_term           = apply_filters( 'woocommerce_product_categories_widget_main_term', $terms[0], $terms );
						$this->current_cat   = $main_term;
						$this->cat_ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
					}
				}

			}
			// Show Siblings and Children Only.
			if ( $show_children_only && $this->current_cat ) {
				if ( $show_first_level_children ) {
					// Direct children.
					$include = get_terms(
						'product_cat',
						array(
							'fields'       => 'ids',
							'hierarchical' => true,
							'parent'       => $this->current_cat->term_id,
							'hide_empty'   => false,
						)
					);

					if ( empty( $include ) ) {
						$list_args['child_of'] = $this->current_cat->term_id;
					} else {
						$list_args['include'] = implode( ',', $include );
					}
				} else {
					$list_args['child_of'] = $this->current_cat->term_id;
				}


			} elseif ( $show_children_only ) {
				$list_args['child_of']     = 0;
				$list_args['hierarchical'] = 1;
			}

			$this->widget_start( $args, $instance );

			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = '';
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			$list_args['max_depth']                  = $max_depth;

			$show_class     = $show_view_more ? ' has-view-more' : '';
			$children_class = $show_children_only ? ' show-children-only' : '';
			$show_class     .= $children_class;
			echo '<ul class="product-categories ' . esc_attr( $show_class ) . '">';

			if ( $this->current_cat && $show_children_only ) {
				$shop_link = isset( $instance['all_cats_link'] ) ? $instance['all_cats_link'] : $this->settings['all_cats_link']['std'];
				$shop_link = empty( $shop_link ) ? get_post_type_archive_link( 'product' ) : $shop_link;

				$shop_text = isset( $instance['all_cats_text'] ) ? $instance['all_cats_text'] : $this->settings['all_cats_text']['std'];
				$shop_text = empty( $shop_text ) ? esc_html__( 'All Categories', 'martfury' ) : $shop_text;
				$back_shop     = sprintf( '<li class="mf-back-shop"><a href="%s">%s</a></li>', esc_url( $shop_link ), $shop_text );
				$cat_ancestors = array_reverse( $this->cat_ancestors );
				array_push( $cat_ancestors, $this->current_cat->term_id );
				foreach ( $cat_ancestors as $parent_id ) {
					$parent_term  = get_term_by( 'id', $parent_id, 'product_cat' );
					$active_class = $parent_id == $this->current_cat->term_id ? 'mf-current-tax' : '';
					$back_shop    .= sprintf( '<li class="mf-back-shop %s"><a href="%s">%s</a></li>', esc_attr( $active_class ), esc_url( get_term_link( $parent_id, 'product_cat' ) ), $parent_term->name );

				}

				echo $back_shop;
			}

			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );

			echo '</ul>';


			if ( $show_view_more ) {

				echo '<div class="mf-widget-product-cats-btn ' . esc_attr( $children_class ) . '">';
				echo '<span class="show-more"><i class="icon-plus-square"></i>' . $show_more . '</span>';
				echo '<span class="show-less"><i class="icon-minus-square"></i>' . $show_less . '</span>';
				echo '<input type="hidden" class="widget-cat-numbers" value="' . esc_attr( $numbers ) . '">';
				echo '</div>';
			}

			$this->widget_end( $args );
		}
	}
}