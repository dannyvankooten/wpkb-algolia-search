<?php

namespace WPKB\Algolia;

use WP_Post;
use WPKB\Plugin;
use WP_CLI, WP_CLI_Command;

class IndexCommand extends WP_CLI_Command {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Index all knowledge base articles
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 * wpkb-algolia index
	 */
	public function index() {
		$batch_number = 1;
		$posts = array( '' );
		$site_url = get_site_url();

		$index = Client::initIndex( WPKB_ALGOLIA_INDEX_NAME );
		$helper = new Helper();

		// keep looping while there are posts left (while incrementing "paged" argument)
		while( is_array( $posts ) && count( $posts ) > 0 ) {
			$posts = get_posts(
				array(
					'post_type' => Plugin::POST_TYPE_NAME,
					'post_status' => 'publish',
					'numberposts' => 500,
					'paged' => $batch_number
				)
			);

			// Create array of post properties we want to use in our index
			$posts = array_map( array( $helper, 'post_for_index' ), $posts );

			$index->addObjects( $posts );
			$batch_number++;
		}

	}

}