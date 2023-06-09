<?php
/**
 * Plugin Name: فیلتر گرید المنتور آرشیو
 * Plugin URI:  #
 * Description: فیلترسازی بخش ارشیو با گرید
 * Version:     1.0.0
 * Author:      فرشید زاهدی
 * Author URI:  https://sitima.com/
 * License URI: https://sitima.com/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

define( 'JSF_EPRO_LOOP_GRID_PROVIDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'JSF_EPRO_LOOP_GRID_PROVIDER_ID', 'epro-loop-builder' );

/**
 * Register custom provider
 */
add_action( 'jet-smart-filters/providers/register', function( $providers_manager ) {

	if ( false === $providers_manager->get_providers( JSF_EPRO_LOOP_GRID_PROVIDER_ID ) ) {

		$providers_manager->register_provider(
			'JSF_EPro_Loop_Grid_Provider', // Custom provider class name
			JSF_EPRO_LOOP_GRID_PROVIDER_PATH . 'provider.php' // Path to file where this class defined
		);

	}

} );

add_filter( 'jet-smart-filters/filters/localized-data', function( $data ) {

	wp_add_inline_script( 'jet-smart-filters', '

		window.JetSmartFilters.events.subscribe( "ajaxFilters/updated", ( provider, queryId ) => {

			if ( "epro-loop-builder" !== provider ) {
				return;
			}

			let filterGroup = window.JetSmartFilters.filterGroups[ provider + "/" + queryId ];

			if ( ! filterGroup || ! filterGroup.$provider ) {
				return;
			}

			let $widget = filterGroup.$provider.closest( ".elementor-widget-loop-grid" );

			if ( $widget.length ) {
				window.elementorFrontend.hooks.doAction(
					"frontend/element_ready/" + $widget.data( "widget_type" ),
					$widget, 
					jQuery
				);
			}

		} );

	' );
	return $data;

} );
