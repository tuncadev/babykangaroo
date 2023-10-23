<?php
/**
 * Compatibility class for WooCommerce Attribute Swatches by Iconic.
 * https://iconicwp.com/products/woocommerce-attribute-swatches/
 *
 * @class   Iconic_WLV_Compat_Woo_Attribute_Swatches
 * @package Iconic_WLV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Iconic_WLV_Compat_Woo_Attribute_Swatches class.
 */
class Iconic_WLV_Compat_Woo_Attribute_Swatches {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'init', array( __CLASS__, 'init' ) );
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public static function init() {
		global $iconic_was;

		if ( empty( $iconic_was ) ) {
			return;
		}

		add_action( 'woocommerce_attribute_label', array( __CLASS__, 'remove_attribute_label_colon' ), 10, 3 );
		add_action( 'iconic_wlv_meta_box_style_options', array( __CLASS__, 'add_swatch_style_option' ) );
	}

	/**
	 * Don't let Iconic_WAS_Attributes->modify_attribute_label run if
	 * the product is Linked variation product.
	 *
	 * @param string     $label   Label.
	 * @param string     $name    Name.
	 * @param WC_Product $product Product.
	 *
	 * @return string $label
	 */
	public static function remove_attribute_label_colon( $label, $name, $product ) {
		global $iconic_was;

		if ( ! is_singular( 'product' ) ) {
			return $label;
		}

		$linked_variations_data = Iconic_WLV_Product::get_linked_variations_data( get_the_ID() );

		if ( $linked_variations_data ) {
			remove_filter( 'woocommerce_attribute_label', array( $iconic_was->attributes_class(), 'modify_attribute_label' ), 100 );
		}

		return $label;
	}

	/**
	 * Add Option for swatches.
	 *
	 * @param string $selected_style Selected style.
	 */
	public static function add_swatch_style_option( $selected_style ) {
		?>
		<option value="inherit_swatch" <?php selected( $selected_style, 'inherit_swatch' ); ?> ><?php esc_html_e( 'Inherit Swatch Settings', 'iconic-wlv' ); ?></option>
		<?php
	}

	/**
	 * Output attributes swatches, if WooCommerce Attribute Swatches by Iconic is active.
	 *
	 * @param array  $attribute_data Attribute data.
	 * @param string $taxonomy       Taxonomy key.
	 *
	 * @return void
	 */
	public static function output_values_inherit_swatch( $attribute_data, $taxonomy ) {
		global $iconic_was;

		if ( empty( $iconic_was ) ) {
			return;
		}

		$product_id         = get_the_ID();
		$attribute_name     = $iconic_was->attributes_class()->format_attribute_slug( $taxonomy, true );
		$swatch_type        = $iconic_was->swatches_class()->get_swatch_option( 'swatch_type', $taxonomy );
		$swatch_type        = $swatch_type ? $swatch_type : 'text-swatch';
		$swatch_shape       = $iconic_was->swatches_class()->get_swatch_option( 'swatch_shape', $taxonomy );
		$tooltips           = (bool) $iconic_was->swatches_class()->get_swatch_option( 'tooltips', $taxonomy );
		$large_preview      = (bool) $iconic_was->swatches_class()->get_swatch_option( 'large_preview', $taxonomy );
		$visual             = $iconic_was->swatches_class()->is_swatch_visual( $swatch_type ) ? ' iconic-was-swatches--visual' : false;
		$tooltips           = $visual && ( $tooltips || $large_preview ) ? ' iconic-was-swatches--tooltips' : false;
		$shape              = $visual && 'round' === $swatch_shape ? 'iconic-was-swatches--round' : 'iconic-was-swatches--square';
		$style              = $iconic_was->settings['style_general_selected'];
		$linked_group       = Iconic_WLV_Product::get_linked_variations_group( $product_id );
		$available_terms    = Iconic_WLV_Product::get_available_terms( $taxonomy, $linked_group );
		$available_terms    = array_map( 'strval', $available_terms );
		$swatch_data_values = self::get_attribute_terms( $taxonomy );

		$groups = self::sort_terms_by_groups( $product_id, $taxonomy, $attribute_data['terms'] );
		?>
		<ul class="iconic-was-swatches iconic-was-swatches--<?php echo esc_attr( $style ); ?> iconic-was-swatches--<?php echo esc_attr( $swatch_type ); ?><?php echo esc_attr( $visual ); ?><?php echo esc_attr( $tooltips ); ?> <?php echo esc_attr( $shape ); ?>" data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
			<?php
			foreach ( $groups as $group_label => $group_terms ) {

				if ( 'iconic-was-default' !== $group_label ) {
					printf( '<li class="iconic-was-swatches__label">%s</li>', esc_html( $group_label ) );
				}

				foreach ( $group_terms as $term_slug => $term_data ) {
					if ( ! in_array( (string) $term_slug, $available_terms, true ) ) {
						continue;
					}

					$swatch_data = $iconic_was->swatches_class()->get_swatch_data(
						array(
							'product_id'     => get_the_ID(),
							'attribute_slug' => $taxonomy,
						)
					);

					// If data is not available for new attributes.
					if ( empty( $swatch_data['values'] ) ) {
						$swatch_data['values'] = $swatch_data_values;
					}

					$swatch_html = $iconic_was->swatches_class()->get_swatch_html( $swatch_data, $term_slug );
					$selected    = $term_data['current'] ? 'iconic-was-swatch--selected' : '';
					$url         = $term_data['url'] ? $term_data['url'] : '#';
					$classes     = implode( ' ', Iconic_WLV_Product::get_value_classes( $term_data, $term_slug ) );

					// Add out of stock class for WAS.
					$classes = str_replace( 'iconic-wlv-terms__term--outofstock', 'iconic-wlv-terms__term--outofstock iconic-was-swatches__item--out-of-stock', $classes );
					?>
					<li class="iconic-was-swatches__item <?php echo esc_attr( $classes ); ?>">
						<a 
							href="<?php echo esc_attr( $url ); ?>" 
							data-attribute-value="<?php echo esc_attr( $term_slug ); ?>" 
							data-attribute-value-name="<?php echo esc_attr( $term_data['label'] ); ?>" 
							class="iconic-was-swatch iconic-was-swatch--follow iconic-was-swatch--<?php echo esc_attr( $swatch_data['swatch_type'] ); ?> <?php echo esc_attr( $selected ); ?>"
							><?php echo wp_kses_post( $swatch_html ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
		<?php
	}

	/**
	 * Returns an array of attribute's terms.
	 *
	 * @param string $taxonomy Taxnonomy ID.
	 *
	 * @return array $result List of terms.
	 */
	public static function get_attribute_terms( $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( empty( $terms ) ) {
			return array();
		}

		$result = array();

		foreach ( $terms as $term ) {
			$result[ $term->slug ] = array(
				'label' => $term->name,
				'value' => $term->slug,
			);
		}

		return $result;
	}

	/**
	 * Seperate out terms data into their individual groups, based on WAS groups.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $taxonomy   Taxonomy.
	 * @param array  $terms      Terms data.
	 *
	 * @return array
	 */
	public static function sort_terms_by_groups( $product_id, $taxonomy, $terms ) {

		$default_group = 'iconic-was-default';
		$groups        = array();

		foreach ( $terms as $slug => $term_data ) {
			$term  = get_term_by( 'slug', $slug, $taxonomy );
			$group = Iconic_WAS_Swatches::get_swatch_value( 'taxonomy', 'group', $term );
			$group = ! empty( $group ) ? $group : $default_group;

			if ( ! isset( $groups[ $group ] ) ) {
				$groups[ $group ] = array();
			}

			$groups[ $group ][ $slug ] = $term_data;
		}

		$groups = self::reorder_assoc_array( $groups, $default_group );

		return $groups;
	}

	/**
	 * Helper: Reorder associative array.
	 *
	 * @param array  $arr The array to be sorted.
	 * @param string $key The index which will be placed first.
	 *
	 * @return array
	 */
	public static function reorder_assoc_array( $arr, $key ) {
		$temp_array = array(
			$key => $arr[ $key ],
		);

		foreach ( $arr as $loop_key => $loop_val ) {
			if ( $key === $loop_key ) {
				continue;
			}

			$temp_array[ $loop_key ] = $loop_val;
		}

		return $temp_array;
	}

}
