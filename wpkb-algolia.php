<?php
/*
Plugin Name: WP Knowledge Base - Algolia Search
Plugin URI: https://mc4wp.com/kb/
Description: Power your KB search using Algolia
Author: ibericode
Version: 0.1
Author URI: https://ibericode.com
Text Domain: wpkb-algolia
Domain Path: /languages/
*/

/**
 * Bootstrap the plugin
 */
add_action( 'plugins_loaded', function() {

	define( 'WPKB_ALGOLIA_VERSION', '0.1' );
	define( 'WPKB_ALGOLIA_FILE', __FILE__ );

	// make sure config constants are defined, if not: bail.
	$constants = array(
		'WPKB_ALGOLIA_APP_ID',
		'WPKB_ALGOLIA_API_KEY',
		'WPKB_ALGOLIA_INDEX_NAME',
		'WPKB_ALGOLIA_API_KEY_SEARCH_ONLY',
	);
	foreach( $constants as $constant ) {
		if( ! defined( $constant ) ) {
			return;
		}
	}
	
	if( ! class_exists( 'WPKB\\Aoglia\\Searcher' ) ) {
		require __DIR__ . '/vendor/autoload.php';
	}

	if( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::add_command( 'wpkb-algolia', 'WPKB\\Algolia\\IndexCommand' );
		// register command
	} elseif( is_admin() ) {
		add_action( 'init', array( 'WPKB\\Algolia\\IndexUpdater', 'init' ) );
	}

	add_action( 'wpkb_search', array( 'WPKB\\Algolia\\Searcher', 'init' ) );
	add_action( 'template_redirect', array( 'WPKB\\Algolia\\AssetManager', 'init' ), 20 );
} );

