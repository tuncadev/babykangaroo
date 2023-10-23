<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Widget_Base;
use MartfuryAddons\Elementor;
use MartfuryAddons\Elementor_AjaxLoader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_Carousel extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-products-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Products Carousel', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'martfury' ];
	}

	public function get_script_depends() {
		return [
			'martfury-elementor'
		];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_heading',
			[ 'label' => esc_html__( 'Heading', 'martfury' ) ]
		);

		$this->start_controls_tabs( 'tabs_heading' );
		$this->start_controls_tab(
			'title_tab',
			[
				'label' => esc_html__( 'Title', 'martfury' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading Name', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_size',
			[
				'label'   => __( 'HTML Tag', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_tab();

		// Group

		$this->start_controls_tab(
			'links_group_tab',
			[
				'label' => esc_html__( 'Links Group', 'martfury' ),
			]
		);


		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading Item', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],

			]
		);
		$this->add_control(
			'link_group',
			[
				'label'         => esc_html__( 'Links Group', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'Link 1', 'martfury' ),
						'link'  => '#'
					],
					[
						'title' => esc_html__( 'Link 2', 'martfury' ),
						'link'  => '#'
					],
					[
						'title' => esc_html__( 'Link 3', 'martfury' ),
						'link'  => '#'
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link',
			[
				'label' => esc_html__( 'View All', 'martfury' ),
			]
		);

		$this->add_control(
			'view_all_text',
			[
				'label'       => esc_html__( 'Text', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View All', 'martfury' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'view_all_link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'view_all_icon',
			[
				'label'     => esc_html__( 'Show icon', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => ''
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Products

		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'martfury' ) ]
		);

		$this->add_control(
			'source',
			[
				'label'       => esc_html__( 'Source', 'martfury' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default' => esc_html__( 'Default', 'martfury' ),
					'custom'  => esc_html__( 'Custom', 'martfury' ),
				],
				'default'     => 'default',
				'label_block' => true,
			]
		);

		$this->add_control(
			'ids',
			[
				'label'       => esc_html__( 'Products', 'martfury' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'martfury' ),
				'type'        => 'mf_autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'product',
				'sortable'    => true,
				'condition'   => [
					'source' => 'custom',
				],
			]
		);

		$this->add_control(
			'product_cats',
			[
				'label'       => esc_html__( 'Product Categories', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy(),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_control(
			'product_tags',
			[
				'label'       => esc_html__( 'Product Tags', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy( 'product_tag' ),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_control(
			'product_brands',
			[
				'label'       => esc_html__( 'Product Brands', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy( 'product_brand' ),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_control(
			'per_page',
			[
				'label'   => esc_html__( 'Total Products', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'products',
			[
				'label'   => esc_html__( 'Products', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'recent'       => esc_html__( 'Recent', 'martfury' ),
					'featured'     => esc_html__( 'Featured', 'martfury' ),
					'best_selling' => esc_html__( 'Best Selling', 'martfury' ),
					'top_rated'    => esc_html__( 'Top Rated', 'martfury' ),
					'sale'         => esc_html__( 'On Sale', 'martfury' ),
				],
				'default' => 'recent',
				'toggle'  => false,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => '',
					'date'       => esc_html__( 'Date', 'martfury' ),
					'title'      => esc_html__( 'Title', 'martfury' ),
					'menu_order' => esc_html__( 'Menu Order', 'martfury' ),
					'rand'       => esc_html__( 'Random', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'conditions' => [
					'terms'	=> [
						[
							'name'	=> 'products',
							'operator' => 'in',
							'value' => [ 'recent', 'top_rated', 'sale', 'featured' ],
						],
						[
							'name'	=> 'source',
							'value' => 'default',
						],
					],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => '',
					'asc'  => esc_html__( 'ASC', 'martfury' ),
					'desc' => esc_html__( 'DESC', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'conditions' => [
					'terms'	=> [
						[
							'name'	=> 'products',
							'operator' => 'in',
							'value' => [ 'recent', 'top_rated', 'sale', 'featured' ],
						],
						[
							'name'	=> 'source',
							'value' => 'default',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'products_break_line',
			[
				'label'     => esc_html__( 'Break Line', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''      => esc_html__( 'Default', 'martfury' ),
					'1'     => esc_html__( '1 Column', 'martfury' ),
					'2'     => esc_html__( '2 Columns', 'martfury' ),
					'3' 	=> esc_html__( '3 Columns', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'prefix_class' => 'mf-products__break_line%s-',
			]
		);

		$this->end_controls_section();

		// Slider Settings
		$this->start_controls_section(
			'section_carousel_settings',
			[ 'label' => esc_html__( 'Carousel Settings', 'martfury' ) ]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'both'   => esc_html__( 'Arrows and Dots', 'martfury' ),
					'arrows' => esc_html__( 'Arrows', 'martfury' ),
					'dots'   => esc_html__( 'Dots', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default' => 'arrows',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'slidesToShow',
			[
				'label'   => esc_html__( 'Slides to Show', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'5' => esc_html__( '5', 'martfury' ),
					'4' => esc_html__( '4', 'martfury' ),
					'3' => esc_html__( '3', 'martfury' ),
					'6' => esc_html__( '6', 'martfury' ),
					'7' => esc_html__( '7', 'martfury' ),
				],
				'default' => '5',
			]
		);

		$this->add_control(
			'slidesToScroll',
			[
				'label'   => esc_html__( 'Slides to Scroll', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'5' => esc_html__( '5', 'martfury' ),
					'4' => esc_html__( '4', 'martfury' ),
					'3' => esc_html__( '3', 'martfury' ),
					'6' => esc_html__( '6', 'martfury' ),
					'7' => esc_html__( '7', 'martfury' ),
					'2' => esc_html__( '2', 'martfury' ),
					'1' => esc_html__( '1', 'martfury' ),
				],
				'default' => '5',
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'     => esc_html__( 'Infinite Loop', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'yes'
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'no'
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   => esc_html__( 'Autoplay Speed (in ms)', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 100,
				'step'    => 100,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => esc_html__( 'Speed', 'martfury' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 100,
				'description' => esc_html__( 'Slide animation speed (in ms)', 'martfury' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_lazy_loading_content',
			[
				'label' => esc_html__( 'Lazy Loading', 'martfury' ),
			]
		);

		$this->add_control(
			'lazy_loading',
			[
				'label'   => esc_html__( 'Enable', 'martfury' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'lazy_loading_height',
			[
				'label'     => esc_html__( 'Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .mf-products-carousel-loading .mf-vc-loading' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		// Heading
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Heading', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'martfury' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'martfury' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'martfury' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'martfury' ),
						'icon'  => 'fa fa-align-right',
					],
					''           => [
						'title' => esc_html__( 'Space Between', 'martfury' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_space',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_responsive_control(
			'heading_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'default'   => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '1',
					'left'   => '0',
				],
			]
		);
		$this->add_control(
			'heading_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(225,225,225)',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_border_style',
			[
				'label'     => esc_html__( 'Border Style', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'dotted' => esc_html__( 'Dotted', 'martfury' ),
					'dashed' => esc_html__( 'Dashed', 'martfury' ),
					'solid'  => esc_html__( 'Solid', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default'   => 'solid',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'heading_style_settings' );

		$this->start_controls_tab( 'heading_title', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );


		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .cat-title a, {{WRAPPER}} .mf-products-carousel .cat-header .cat-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .cat-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-products-carousel .cat-header .cat-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'links_title', [ 'label' => esc_html__( 'Links', 'martfury' ) ] );

		$this->add_responsive_control(
			'show_product_links',
			[
				'label'     => esc_html__( 'Links', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'inline-block' => esc_html__( 'Show', 'martfury' ),
					'none'         => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'inline-block',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li:not(.view-all-link)' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'product_link_items_spacing',
			[
				'label'     => esc_html__( 'Items Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li'                  => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li:first-child'      => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products-carousel .cat-header .extra-links li:last-child'  => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products-carousel .cat-header .extra-links li:first-child' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_link_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li a, {{WRAPPER}} .mf-products-carousel .cat-header .extra-links li' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_link_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li a:hover' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_link_typography',
				'selector' => '{{WRAPPER}} .mf-products-carousel .cat-header .extra-links li',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_style',
			[
				'label' => esc_html__( 'Products', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'products_background',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel ul.products li.product .product-inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'products_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel ul.products li.product .product-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'products_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel ul.products li.product .product-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'products_spaceBetween',
			[
				'label'     => __( 'Spacing between products', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel ul.products li.product' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-products-carousel ul.products' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-products-carousel .slick-prev-arrow' => 'margin-left: calc( {{SIZE}}{{UNIT}} - 1px );',
					'{{WRAPPER}} .mf-products-carousel .slick-next-arrow' => 'margin-right: calc( {{SIZE}}{{UNIT}} - 1px );',
				],

			]
		);

		$this->add_control(
			'products_button_hover_color',
			[
				'label'     => esc_html__( 'Hover Button Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel ul.products li.product .mf-product-price-box a.button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Carousel style
		$this->start_controls_section(
			'section_carousel_style',
			[
				'label' => esc_html__( 'Carousel Settings', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'product_carousel_settings' );

		$this->start_controls_tab( 'product_carousel_arrows_style', [ 'label' => esc_html__( 'Arrows', 'martfury' ) ] );

		$this->add_control(
			'products_arrows_background',
			[
				'label'     => esc_html__( 'Show background arrow', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => ''
			]
		);

		$this->add_control(
			'products_arrows_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-arrow .mf-arrow-bg' => 'color: {{VALUE}}',
				],
				'condition' => [
					'products_arrows_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'products_arrows_background_color_hover',
			[
				'label'     => esc_html__( 'Hover Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-arrow:hover .mf-arrow-bg' => 'color: {{VALUE}}',
				],
				'condition' => [
					'products_arrows_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'products_arrows_position_top',
			[
				'label'      => esc_html__( 'Position Vertival', 'martfury' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 50
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel .slick-arrow' => 'top: {{SIZE}}{{UNIT}};transform: translateY(-{{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'products_arrows_position_horizontal',
			[
				'label'      => esc_html__( 'Position Horizontal', 'martfury' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [],
				'range'      => [
					'px' => [
						'min' => -200,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-carousel .slick-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-products-carousel .slick-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'products_arrows_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'products_arrows_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-arrow:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_arrows_typography',
				'selector' => '{{WRAPPER}} .mf-products-carousel .slick-arrow',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'product_carousel_dots_style', [ 'label' => esc_html__( 'Dots', 'martfury' ) ] );

		$this->add_responsive_control(
			'products_dots_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'products_dots_width',
			[
				'label'     => esc_html__( 'Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'products_dots_background',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-dots li button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'products_dots_active_background',
			[
				'label'     => esc_html__( 'Active Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_lazy_loading',
			[
				'label' => esc_html__( 'Lazy Loading', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'loading_border_color',
			[
				'label'     => esc_html__( 'Loading Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-carousel .mf-vc-loading .mf-vc-loading--wrapper:before' => 'border-color: {{VALUE}} {{VALUE}} {{VALUE}} transparent;',
				],
				'separator' => 'before',
			]
		);


		$this->end_controls_section();

	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();


		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav_tablet : $settings['navigation_mobile'];

		$classes = [
			'mf-products-carousel woocommerce  mf-elementor-navigation',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
			$settings['lazy_loading'] == 'yes' ? '' : 'no-infinite',
			$settings['products_arrows_background'] == 'yes' ? 'arrow-has-background' : '',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$carousel_settings = [
			'autoplay'          => $settings['autoplay'],
			'infinite'          => $settings['infinite'],
			'autoplay_speed'    => intval( $settings['autoplay_speed'] ),
			'speed'             => intval( $settings['speed'] ),
			'slidesToShow'      => intval( $settings['slidesToShow'] ),
			'slidesToScroll'    => intval( $settings['slidesToScroll'] ),
			'arrows_background' => $settings['products_arrows_background'],
		];

		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $settings['lazy_loading'] == 'yes' ) : ?>
				<?php
				// AJAX settings
				$this->add_render_attribute(
					'ajax_wrapper', 'class', [
						'mf-products-carousel-loading'
					]
				);
				$ajax_settings = [
					'title'             => $settings['title'],
					'title_size'        => $settings['title_size'],
					'link'              => $settings['link'],
					'link_group'        => $settings['link_group'],
					'view_all_text'     => $settings['view_all_text'],
					'view_all_link'     => $settings['view_all_link'],
					'product_cats'      => $settings['product_cats'],
					'product_tags'      => $settings['product_tags'],
					'product_brands'    => $settings['product_brands'],
					'per_page'          => $settings['per_page'],
					'products'          => $settings['products'],
					'orderby'           => $settings['orderby'],
					'order'             => $settings['order'],
					'navigation'        => $settings['navigation'],
					'navigation_tablet' => $nav_tablet,
					'navigation_mobile' => $nav_mobile,
					'slidesToShow'      => $settings['slidesToShow'],
					'slidesToScroll'    => $settings['slidesToScroll'],
					'infinite'          => $settings['infinite'],
					'autoplay'          => $settings['autoplay'],
					'autoplay_speed'    => $settings['autoplay_speed'],
					'speed'             => $settings['speed'],
					'arrows_background' => $settings['products_arrows_background'],
				];

				if( ! empty( $settings['ids'] ) ) {
					$ajax_settings['ids'] = $settings['ids'];
				}

				$this->add_render_attribute( 'ajax_wrapper', 'data-settings', wp_json_encode( $ajax_settings ) );
				?>
                <div <?php echo $this->get_render_attribute_string( 'ajax_wrapper' ); ?>>
                    <div class="mf-vc-loading">
                        <div class="mf-vc-loading--wrapper"></div>
                    </div>
                </div>
			<?php else : ?>
				<?php Elementor_AjaxLoader::get_product_grid_handler( $settings ); ?>
			<?php endif; ?>
        </div>
		<?php
	}


}