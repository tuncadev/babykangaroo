<?php
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Class Iconic_WLV_Linked_Variations_Group
 */
class Iconic_WLV_Linked_Variations_Group {
	/**
	 * Post object.
	 *
	 * @var null|WP_Post
	 */
	public $post = null;

	/**
	 * Meta object.
	 *
	 * @var null|bool|object
	 */
	public $meta = null;

	/**
	 * Attributes.
	 *
	 * @var array
	 */
	public $attributes = array(
		'array' => array(),
		'string' => '',
	);

	/**
	 * Product IDs.
	 *
	 * @var array
	 */
	public $product_ids = array(
		'array' => array(),
		'string' => '',
	);

	/**
	 * Iconic_WLV_Linked_Variations_Group constructor.
	 *
	 * @param $post
	 */
	public function __construct( $post ) {
		$this->post = is_int( $post ) ? get_post( $post ) : $post;
		$this->meta = Iconic_WLV_Database::get_linked_variations_meta( $this->post->ID );
	}

	/**
	 * Get product IDs.
	 *
	 * @param bool $array
	 *
	 * @return array|string|bool
	 */
	public function get_product_ids( $array = true ) {
		$type = $array ? 'array' : 'string';

		if ( ! empty( $this->product_ids[ $type ] ) || ! $this->meta ) {
			return $this->product_ids[ $type ];
		}

		$unserialized_product_ids = (array) maybe_unserialize( $this->meta->product_ids );

		$this->product_ids['array']  = $unserialized_product_ids;
		$this->product_ids['string'] = implode( ',', $unserialized_product_ids );

		return $this->product_ids[ $type ];
	}

	/**
	 * Get attributes.
	 *
	 * @param bool $array
	 *
	 * @return array|string
	 */
	public function get_attributes( $array = true ) {
		$type = $array ? 'array' : 'string';

		if ( ! empty( $this->attributes[ $type ] ) || ! $this->meta ) {
			return $this->attributes[ $type ];
		}

		$unserialized_attributes = (array) maybe_unserialize( $this->meta->attributes );

		$this->attributes['array']  = $unserialized_attributes;
		$this->attributes['string'] = implode( ',', $unserialized_attributes );

		return $this->attributes[ $type ];
	}

	/**
	 * Is show image checked?
	 *
	 * @return bool
	 */
	public function is_show_image() {
		if ( ! $this->meta ) {
			return false;
		}

		return (bool) $this->meta->show_image;
	}

	/**
	 * Is show image checked?
	 *
	 * @return bool
	 */
	public function get_style() {
		if ( ! $this->meta ) {
			return false;
		}

		return $this->meta->style ? $this->meta->style : 'buttons';
	}
}