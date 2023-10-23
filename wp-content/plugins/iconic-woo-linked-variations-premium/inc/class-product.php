<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Product
 */
class Iconic_WLV_Product {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'output_linked_variations' ), 25 );
	}

	/**
	 * Output linked variations.
	 */
	public static function output_linked_variations() {
		global $product, $post;

		if ( empty( $product ) && is_object( $post ) && 'product' === $post->post_type ) {
			$product = wc_get_product( $post->ID );
		}

		if ( empty( $product ) ) {
			return;
		}

		$current_product_attributes = self::get_product_attributes( $product->get_id() );
		$linked_variations_data     = self::get_linked_variations_data( $product->get_id() );

		if ( empty( $linked_variations_data ) ) {
			return;
		}

		do_action( 'iconic_wlv_before_variations_display', array(
			'product'               => $product,
			'variations_group'      => $linked_variations_data['group'],
			'variations_attributes' => $linked_variations_data['attributes'],
		) ); ?>

		<table class="variations iconic-wlv-variations" cellspacing="0">
			<tbody>
			<?php $i = 0;
			foreach ( $linked_variations_data['attributes'] as $taxonomy => $attribute_data ) { ?>
				<?php
				if ( empty( $attribute_data['terms'] ) ) {
					continue;
				}

				$current_label = isset( $current_product_attributes['labels'][ $taxonomy ] ) ? $current_product_attributes['labels'][ $taxonomy ] : '';
				?>
				<tr class="iconic-wlv-variations__row iconic-wlv-variations__row--<?php echo esc_attr( $taxonomy ); ?>">
					<td class="label iconic-wlv-variations__label">
						<strong class="iconic-wlv-variations__label"><?php echo esc_html( $attribute_data['label'] ); ?></strong><?php echo wp_kses_post( apply_filters( 'iconic_wlv_attribute_label_colon', '<span class="iconic-wlv-variations__colon">:</span>' ) ); ?>
						<span class="iconic-wlv-variations__selection" data-iconic-wlv-selected-term-label="<?php echo esc_attr( $current_label ); ?>"><?php echo esc_html( $current_label ); ?></span>
					</td>
					<?php do_action( 'iconic_wlv_after_attribute_label', $taxonomy, $attribute_data ); ?>
					<td class="value iconic-wlv-variations__value">
						<?php
						$style         = 0 === $i && $linked_variations_data['group']->is_show_image() ? 'buttons' : $linked_variations_data['group']->get_style();
						$values_method = sprintf( 'output_values_%s', $style );

						if ( 'output_values_inherit_swatch' === $values_method ) {
							Iconic_WLV_Compat_Woo_Attribute_Swatches::output_values_inherit_swatch( $attribute_data, $taxonomy );
						} elseif ( method_exists( __CLASS__, $values_method ) ) {
							self::$values_method( $attribute_data, $taxonomy );
						}
						?>
					</td>
				</tr>
				<?php $i ++;
				do_action( 'iconic_wlv_after_attribute_row', $taxonomy, $attribute_data );
			} ?>
			</tbody>
		</table>

		<?php do_action( 'iconic_wlv_after_variations_display', array(
			'product'               => $product,
			'variations_group'      => $linked_variations_data['group'],
			'variations_attributes' => $linked_variations_data['attributes'],
		) );
	}

	/**
	 * Output values as buttons.
	 *
	 * @param array  $attribute_data Attribute Data.
	 * @param string $taxonomy       Taxonomy key.
	 */
	public static function output_values_buttons( $attribute_data, $taxonomy = '' ) {
		?>
		<ul class="iconic-wlv-terms iconic-wlv-term--buttons">
			<?php
			foreach ( $attribute_data['terms'] as $term_slug => $term_data ) {
				if ( ! self::is_value_allowed( $term_data ) ) {
					continue;
				}

				$class             = self::get_value_classes( $term_data, $term_slug );
				$attributes_string = '';
				$attributes        = array(
					'class'                      => implode( ' ', $class ),
					'data-iconic-wlv-term-label' => $term_data['label'],
				);

				$attributes = apply_filters( 'iconic_wlv_term_button_attributes', $attributes, $term_data, $attribute_data, $taxonomy );

				foreach ( $attributes as $key => $value ) {
					$attributes_string .= sprintf( '%s="%s"', $key, esc_attr( $value ) );
				}
				?>
				<?php  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<li <?php echo $attributes_string; ?>>
					<?php echo wp_kses_post( $term_data['content'] ); ?>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}

	/**
	 * Output values as dropdown.
	 *
	 * @param array  $attribute_data Attribute data.
	 * @param string $taxonomy       Taxonomy key.
	 */
	public static function output_values_dropdown( $attribute_data, $taxonomy = '' ) {
		$available   = __( 'Available', 'iconic-wlv' );
		$unavailable = __( 'Unavailable', 'iconic-wlv' );

		$options = array(
			$available   => array(),
			$unavailable => array(),
		);

		foreach ( $attribute_data['terms'] as $term_slug => $term_data ) {
			if ( ! self::is_value_allowed( $term_data ) ) {
				continue;
			}

			if ( ! empty( $term_data['linked_variation_data']['variation'] ) ) {
				if ( ! $term_data['linked_variation_data']['variation']['purchasable'] || ! $term_data['linked_variation_data']['match'] ) {
					$options[ $unavailable ][ $term_slug ] = $term_data;
					continue;
				}
			}

			$options[ $available ][ $term_slug ] = $term_data;
		} ?>
		<select id="test" class="iconic-wlv-terms iconic-wlv-terms--dropdown" onchange="this.options[this.selectedIndex].value && ( ! window.location.href.includes( this.options[this.selectedIndex].value ) ) && (window.location = this.options[this.selectedIndex].value);">
			<?php foreach ( $options as $option_group => $terms ) { ?>
				<?php if ( empty( $terms ) ) {
					continue;
				} ?>
				<optgroup label="<?php echo esc_attr( $option_group ); ?>">
					<?php foreach ( $terms as $term_slug => $term_data ) {
						if ( ! self::is_value_allowed( $term_data ) ) {
							continue;
						}

						$class = self::get_value_classes( $term_data, $term_slug ); ?>
						<option value="<?php echo esc_attr( $term_data['url'] ); ?>" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" <?php selected( $term_data['current'] ); ?>>
							<?php echo $term_data['label']; ?>
						</option>
					<?php } ?>
				</optgroup>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Is attribute value allowed to be displayed?
	 *
	 * @param $term_data
	 *
	 * @return bool
	 */
	public static function is_value_allowed( $term_data ) {
		if ( empty( $term_data['linked_variation_data'] ) ) {
			return false;
		}

		if ( ! empty( $term_data['linked_variation_data']['variation'] ) ) {
			if ( ! in_array( $term_data['linked_variation_data']['variation']['stock_status'], array( 'instock', 'onbackorder' ), true ) && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get classes for attribute value.
	 *
	 * @param array  $term_data
	 * @param string $term_slug
	 *
	 * @return array
	 */
	public static function get_value_classes( $term_data, $term_slug ) {
		$class = array(
			'iconic-wlv-terms__term',
			'iconic-wlv-terms__term--' . $term_slug,
		);

		if ( ! empty( $term_data['linked_variation_data']['variation'] ) ) {
			$class[] = 'iconic-wlv-terms__term--' . $term_data['linked_variation_data']['variation']['stock_status'];

			if ( ! $term_data['linked_variation_data']['variation']['purchasable'] ) {
				$class[] = 'iconic-wlv-terms__term--unavailable';
			}
		}

		if ( $term_data['current'] ) {
			$class[] = 'iconic-wlv-terms__term--current';
		}

		if ( $term_data['has_image'] ) {
			$class[] = 'iconic-wlv-terms__term--image';
		}

		if ( ! $term_data['linked_variation_data']['match'] ) {
			$class[] = 'iconic-wlv-terms__term--partial-match';
		}

		return (array) apply_filters( 'iconic_wlv_term_class', $class, $term_slug, $term_data );
	}

	/**
	 * Get group data for a specific product.
	 *
	 * @param int $product_id
	 *
	 * @return array
	 */
	public static function get_linked_variations_data( $product_id ) {
		$product_id = apply_filters( 'iconic_wlv_product_id', $product_id );

		static $group_data = array();

		if ( isset( $group_data[ $product_id ] ) ) {
			return $group_data[ $product_id ];
		}

		$group_data[ $product_id ] = array();
		$group                     = self::get_linked_variations_group( $product_id );

		if ( ! $group ) {
			return $group_data[ $product_id ];
		}

		$attributes = $group->get_attributes();

		if ( empty( $attributes ) ) {
			return $group_data[ $product_id ];
		}

		$group_data[ $product_id ] = array(
			'group'      => $group,
			'attributes' => array(),
		);

		$i = 0;
		foreach ( $attributes as $attribute_slug ) {
			$attribute_label = wc_attribute_label( $attribute_slug );

			if ( empty( $attribute_label ) ) {
				continue;
			}

			$group_data[ $product_id ]['attributes'][ $attribute_slug ] = array(
				'label' => $attribute_label,
				'terms' => array(),
			);

			do_action( 'iconic_wlv_before_get_terms', $product_id, $attribute_slug, $attribute_label );

			$terms = get_terms( array(
				'taxonomy'   => $attribute_slug,
				'hide_empty' => true,
			) );

			do_action( 'iconic_wlv_after_get_terms', $product_id, $attribute_slug, $attribute_label );

			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			$has_linkable_term = false;

			foreach ( $terms as $term ) {
				$current               = has_term( $term->term_id, $attribute_slug, $product_id );
				$linked_variation_data = self::get_linked_variation_data_for_attribute( $product_id, $attribute_slug, $term->slug );
				$has_image             = 0 === $i && $group->is_show_image();

				if ( empty( $linked_variation_data ) ) {
					continue;
				}

				$content = $has_image ? $linked_variation_data['variation']['image'] : $term->name;

				if ( $linked_variation_data ) {
					$has_linkable_term = true;
				}

				if ( ! $current ) {
					$content = sprintf(
						'<a href="%s" title="%s" class="iconic-wlv-terms__term-content iconic-wlv-terms__term-content--link">%s</a>',
						esc_attr( $linked_variation_data['variation']['permalink'] ),
						esc_attr( $linked_variation_data['variation']['title'] ),
						$content
					);
				} else {
					$content = sprintf(
						'<span class="iconic-wlv-terms__term-content">%s</span>',
						$content
					);
				}

				$group_data[ $product_id ]['attributes'][ $attribute_slug ]['terms'][ $term->slug ] = apply_filters( 'iconic_wlv_group_attribute_term_data', array(
					'label'                 => $term->name,
					'current'               => $current,
					'content'               => $content,
					'has_image'             => $has_image,
					'url'                   => $has_linkable_term ? $linked_variation_data['variation']['permalink'] : false,
					'linked_variation_data' => $linked_variation_data,
				), $product_id );
			}

			if ( ! $has_linkable_term ) {
				unset( $group_data[ $product_id ]['attributes'][ $attribute_slug ] );
			}

			$i ++;
		}

		$group_data[ $product_id ] = apply_filters( 'iconic_wlv_group_data', $group_data[ $product_id ], $product_id );

		return $group_data[ $product_id ];
	}

	/**
	 * Get linked variations for a product by ID.
	 *
	 * @param int $product_id
	 *
	 * @return array
	 */
	public static function get_product_linked_variations( $product_id ) {
		$product_id = apply_filters( 'iconic_wlv_product_id', $product_id );

		static $linked_variations = array();

		if ( isset( $linked_variations[ $product_id ] ) ) {
			return $linked_variations[ $product_id ];
		}

		$linked_variations[ $product_id ] = array();

		$group = self::get_linked_variations_group( $product_id );

		if ( ! $group ) {
			return $linked_variations[ $product_id ];
		}

		$product_ids = $group->get_product_ids();

		if ( empty( $product_ids ) ) {
			return $linked_variations[ $product_id ];
		}

		foreach ( $product_ids as $linked_variation_id ) {
			$linked_variation_id = apply_filters( 'iconic_wlv_linked_variation_id', $linked_variation_id );
			$linked_variation    = wc_get_product( $linked_variation_id );

			if ( ! $linked_variation || $linked_variation->get_status() !== 'publish' ) {
				continue;
			}

			$linked_variations[ $product_id ][ $linked_variation_id ] = array(
				'id'           => $linked_variation_id,
				'permalink'    => $linked_variation->get_permalink(),
				'image'        => $linked_variation->get_image( apply_filters( 'iconic_wlv_linked_variation_image_size', 'shop_thumbnail', $linked_variation, $group ) ),
				'title'        => $linked_variation->get_title(),
				'stock_status' => $linked_variation->get_stock_status(),
				'purchasable'  => $linked_variation->is_purchasable(),
				'attributes'   => self::get_product_attributes( $linked_variation_id, $group ),
			);
		}

		return $linked_variations[ $product_id ];
	}

	/**
	 * Get product attributes based on
	 * linked variations group selection.
	 *
	 * @param int                                     $product_id Product ID.
	 * @param null|Iconic_WLV_Linked_Variations_Group $group      Group.
	 *
	 * @return array
	 */
	public static function get_product_attributes( $product_id, $group = null ) {
		$product_id = apply_filters( 'iconic_wlv_product_id', $product_id );

		static $attributes = array();

		if ( isset( $attributes[ $product_id ] ) ) {
			return $attributes[ $product_id ];
		}

		$attributes[ $product_id ] = array();

		if ( is_null( $group ) ) {
			$group = self::get_linked_variations_group( $product_id );
		}

		if ( ! $group ) {
			return $attributes[ $product_id ];
		}

		$group_attributes = $group->get_attributes();

		if ( empty( $group_attributes ) ) {
			return $attributes[ $product_id ];
		}

		if ( ! isset( $attributes[ $product_id ]['slugs'] ) ) {
			$attributes[ $product_id ]['slugs']  = array();
			$attributes[ $product_id ]['labels'] = array();
		}

		foreach ( $group_attributes as $attribute ) {
			$terms = get_the_terms( $product_id, $attribute );

			if ( ! $terms ) {
				continue;
			}

			$first_term = array_pop( $terms );

			$attributes[ $product_id ]['slugs'][ $attribute ]  = $first_term->slug;
			$attributes[ $product_id ]['labels'][ $attribute ] = $first_term->name;
		}

		$attributes = apply_filters( 'iconic_wlv_group_attributes', $attributes[ $product_id ], $product_id );

		return $attributes;
	}

	/**
	 * Get the product ID based on current product
	 * attributes for an attribute value.
	 *
	 * @param int    $product_id
	 * @param string $taxonomy
	 * @param string $term_slug
	 *
	 * @return bool|array
	 */
	public static function get_linked_variation_data_for_attribute( $product_id, $taxonomy, $term_slug ) {
		$product_id         = apply_filters( 'iconic_wlv_product_id', $product_id );
		$term_slug          = apply_filters( 'iconic_wlv_variation_term_slug', $term_slug, $taxonomy, $product_id );
		$return             = false;
		$current_attributes = self::get_product_attributes( $product_id );
		$linked_variations  = self::get_product_linked_variations( $product_id );

		$current_attributes['slugs'][ $taxonomy ] = $term_slug;

		foreach ( $linked_variations as $linked_variation_id => $linked_variation ) {
			$diff = array_diff_assoc( $current_attributes['slugs'], $linked_variation['attributes']['slugs'] );

			if ( empty( $diff ) ) {
				$return = array(
					'match'     => true,
					'variation' => $linked_variation,
				);
				break;
			}
		}

		if ( $return ) {
			return $return;
		}

		foreach ( $linked_variations as $linked_variation_id => $linked_variation ) {
			if ( ! isset( $linked_variation['attributes']['slugs'][ $taxonomy ] ) ) {
				continue;
			}

			if ( $linked_variation['attributes']['slugs'][ $taxonomy ] === $term_slug ) {
				$return = array(
					'match'     => false,
					'variation' => $linked_variation,
				);
				break;
			}
		}

		return apply_filters( 'iconic_wlv_linked_variation_data', $return, $product_id, $taxonomy, $term_slug );
	}

	/**
	 * Get linked variations group for a specific product by ID.
	 *
	 * @param int $product_id
	 *
	 * @return bool|Iconic_WLV_Linked_Variations_Group
	 */
	public static function get_linked_variations_group( $product_id ) {
		$product_id = apply_filters( 'iconic_wlv_product_id', $product_id );

		static $groups = array();

		if ( ! empty( $groups[ $product_id ] ) ) {
			return $groups[ $product_id ];
		}

		$groups[ $product_id ] = false;
		$group_id              = Iconic_WLV_Database::get_linked_variations_group_id_for_product( $product_id );

		if ( $group_id ) {
			$groups[ $product_id ] = new Iconic_WLV_Linked_Variations_Group( $group_id );
		}

		return $groups[ $product_id ];
	}

	/**
	 * Return all the available terms for the given taxonomy under given Linked Group
	 *
	 * @param string                             $taxonomy     The taxonomy slug.
	 * @param Iconic_WLV_Linked_Variations_Group $linked_group The output of self::get_linked_variations_group().
	 *
	 * @return array $available_terms Avalable Terms.
	 */
	public static function get_available_terms( $taxonomy, $linked_group ) {
		$product_ids     = $linked_group->get_product_ids( true );
		$available_terms = array();
		$terms           = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		// We will consider the term if atleast one product is assigned to it.
		foreach ( $terms as $term ) {
			$flag = false;

			foreach ( $product_ids as $product_id ) {
				if ( has_term( $term->term_id, $taxonomy, $product_id ) ) {
					$flag = true;
					break; // We found one product, no need to continue looping.
				}
			}

			if ( $flag ) {
				$available_terms[] = $term->slug;	
			}
		}

		return $available_terms;
	}

	/**
	 * Return true if for the given term exists atleast one in-stock or backorder product.
	 *
	 * @param string                             $taxonomy     Taxonomy slug.
	 * @param string                             $term_label   Term Label.
	 * @param Iconic_WLV_Linked_Variations_Group $linked_group Linked Variation Group.
	 *
	 * @return bool $flag Does the term have instock product?
	 */
	public static function term_has_instock_products( $taxonomy, $term_label, $linked_group ) {
		$product_ids = $linked_group->get_product_ids();
		$flag        = false;

		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			if ( $product->is_in_stock() && $product->get_attribute( $taxonomy ) === $term_label ) {
				$flag = true;
				break; // We found one product which is in stock, No need to continue looping.
			}
		}

		return $flag;
	}
}
