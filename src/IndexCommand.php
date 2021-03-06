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
		$index = wpkb_get_algolia_index();
		$helper = new Helper();

		// keep looping while there are posts left (while incrementing "paged" argument)
		while( is_array( $posts ) && count( $posts ) > 0 ) {
			$posts = get_posts(
				array(
					'post_type' => 'wpkb-article',
					'post_status' => 'publish',
					'numberposts' => 500,
					'paged' => $batch_number
				)
			);

			// Create array of post properties we want to use in our index
			$posts = array_map( array( $helper, 'post_for_index' ), $posts );

			try{
				$index->addObjects( $posts );
			} catch( \Exception $e ) {
				// uh oh
				WP_CLI::error( $e->getMessage() );
				return;
			}

			$batch_number++;
		}

	}

}
