<?php

namespace WPKB\Algolia;

class AssetManager {

	public function __construct() {

	}

	/**
	 * Initialize
	 */
	public static function init() {
		$instance = new AssetManager();
		add_action( 'wp_enqueue_scripts', array( $instance, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_assets() {

		wp_enqueue_script( 'wpkb-algolia-search', plugins_url( '/assets/js/search.js', WPKB_ALGOLIA_FILE ), array(), WPKB_ALGOLIA_VERSION, true );
		wp_localize_script( 'wpkb-algolia-search', 'wpkb_algolia_config',
			array(
				'app_id' => WPKB_ALGOLIA_APP_ID,

				// Important! Use "search only" API key here.
				'api_key' => WPKB_ALGOLIA_API_KEY_SEARCH_ONLY,
				'index_name' => WPKB_ALGOLIA_INDEX_NAME
			)
		);

		// dequeue default search
		wp_deregister_script( 'wpkb-search' );
	}
}