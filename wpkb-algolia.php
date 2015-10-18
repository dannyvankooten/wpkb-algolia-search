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

	// make sure config constants are defined
	$constants = array( 'WPKB_ALGOLIA_APP_ID', 'WPKB_ALGOLIA_API_KEY', 'WPKB_ALGOLIA_INDEX_NAME' );
	foreach( $constants as $constant ) {
		if( ! defined( $constant ) ) {
			define( $constant, '' );
		}
	}

	require __DIR__ . '/vendor/autoload.php';

	if( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::add_command( 'wpkb-algolia', 'WPKB\\Algolia\\IndexCommand' );
		// register command
	}

	// register search stuff
	add_action( 'template_redirect', array( 'WPKB\\Algolia\\AssetManager', 'init' ), 20 );

} );

