<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use MartfuryAddons\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_List extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-products-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Products List', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'martfury' ];
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
			'section_header',
			[ 'label' => esc_html__( 'Header', 'martfury' ) ]
		);

		$this->start_controls_tabs( 'tabs_heading' );
		$this->start_controls_tab(
			'tab_title',
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
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_tab();

		// Link Group

		$this->start_controls_tab(
			'tab_link_group',
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
				'prevent_empty' => false,
				'title_field'   => '{{{ title }}}',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product',
			[ 'label' => esc_html__( 'Products', 'martfury' ) ]
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
			]
		);

		$this->add_control(
			'per_page',
			[
				'label'   => esc_html__( 'Total Products', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
				],
				'default' => '3',
				'toggle'  => false,
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
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'martfury' ),
					'asc'  => esc_html__( 'Ascending', 'martfury' ),
					'desc' => esc_html__( 'Descending', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
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
		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-list .cat-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '13',
					'left'   => '0',
				],
			]
		);
		$this->add_responsive_control(
			'heading_space',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 55,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-list .cat-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-list .cat-header' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mf-products-list .cat-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-list .cat-header' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .mf-products-list .cat-header' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-list .cat-header .cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mf-products-list .cat-header .cat-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-list .cat-header .cat-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-products-list .cat-header .cat-title',
			]
		);

		$this->end_controls_section();

		// Product Tab
		$this->start_controls_section(
			'section_tab_style',
			[
				'label' => esc_html__( 'Link Group', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

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
					'{{WRAPPER}} .mf-products .cat-header .extra-links' => 'display: {{VALUE}}',
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
					'{{WRAPPER}} .mf-products .cat-header .extra-links li'                   => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .mf-products .cat-header .extra-links li:first-child'       => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products  .cat-header .extra-links li:last-child'  => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products  .cat-header .extra-links li:first-child' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .mf-products .cat-header .extra-links',
			]
		);

		$this->start_controls_tabs( 'tab_style' );
		$this->start_controls_tab(
			'tab_normal',
			[
				'label' => esc_html__( 'Normal', 'martfury' ),
			]
		);
		$this->add_control(
			'tab_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products .cat-header .extra-links li a,{{WRAPPER}} .mf-products .cat-header .extra-links li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'tab_hover',
			[
				'label' => esc_html__( 'Hover', 'martfury' ),
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products .cat-header .extra-links li a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'mf-products-list mf-products woocommerce'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$header_tabs   = [];
		$header_tabs[] = '<div class="cat-header">';
		if ( ! empty( $settings['title'] ) ) {
			$header_tabs[] = sprintf( '<%1$s class="cat-title">%2$s</%1$s>', \Elementor\Utils::validate_html_tag( $settings['title_size'] ),  $this->get_link_control( 'link', $settings['link'], $settings['title'], '' ) );
		}

		$link_group = $settings['link_group'];

		if ( ! empty ( $link_group ) ) {
			$header_tabs[] = '<ul class="extra-links">';

			foreach ( $link_group as $index => $item ) {
				$link_key = 'link_' . $index;

				$link = $this->get_link_control( $link_key, $item['link'], $item['title'], 'extra-link' );

				$header_tabs[] = sprintf( '<li>%s</li>', $link );
			}

			$header_tabs[] = '</ul>';
		}

		$header_tabs[] = '</div>';

		$atts = [
			'per_page'     => $settings['per_page'],
			'products'     => $settings['products'],
			'order'        => $settings['order'],
			'orderby'      => $settings['orderby'],
			'product_cats' => $settings['product_cats'],
			'columns'      => $settings['columns'],
		];

		$content = sprintf( '<div class="products-content">%s</div>', Elementor::get_products( $atts ) );

		echo sprintf(
			'<div %s>%s%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $header_tabs ),
			$content
		);
	}

	protected function get_link_control( $link_key, $url, $content, $class_css ) {

		if ( $url['is_external'] ) {
			$this->add_render_attribute( $link_key, 'target', '_blank' );
		}

		if ( $url['nofollow'] ) {
			$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
		}

		$attr = 'span';
		if ( $url['url'] ) {
			$this->add_render_attribute( $link_key, 'href', $url['url'] );
			$attr = 'a';
		}

		return sprintf( '<%1$s class="%4$s" %2$s>%3$s</%1$s>', $attr, $this->get_render_attribute_string( $link_key ), $content, $class_css );
	}
}