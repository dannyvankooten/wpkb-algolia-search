<?php
/*
Plugin Name: WP Knowledge Base - Algolia Search
Plugin URI: https://mc4wp.com/kb/
Description: Power your KB search using Algolia
Author: ibericode
Version: 0.3.1
Author URI: https://ibericode.com
Text Domain: wpkb-algolia
Domain Path: /languages/

Copyright (C) 2014, Danny van Kooten - support@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Bootstrap the plugin
 */
add_action( 'plugins_loaded', function() {

	define( 'WPKB_ALGOLIA_VERSION', '0.3.1' );
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
		require __DIR__ . '/src/functions.php';
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

