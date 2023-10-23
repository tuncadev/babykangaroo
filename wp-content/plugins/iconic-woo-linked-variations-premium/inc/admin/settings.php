<?php


add_filter( 'wpsf_register_settings_iconic_wlv', 'iconic_wlv_settings' );

/**
 * Linked Variations Settings
 *
 * @param array $wpsf_settings
 *
 * @return array
 */
function iconic_wlv_settings( $wpsf_settings ) {
	$wpsf_settings['sections']['additional'] = array(
		'tab_id'              => 'dashboard',
		'section_id'          => 'additional',
		'section_title'       => __( 'Additional', 'iconic-wlv' ),
		'section_description' => '',
		'section_order'       => 30,
		'fields'              => array(
			array(
				'id'       => 'export',
				'title'    => __( 'Export', 'iconic-wlv' ),
				'subtitle' => __( 'Export the data for your linked variation groups.', 'iconic-wlv' ),
				'type'     => 'custom',
				'default'  => Iconic_WLV_Settings::export_button(),
			),
		),
	);

	return $wpsf_settings;
}