<?php

namespace WPKB\Algolia;

use WP_Post;
use WPKB\Plugin;
use AlgoliaSearch\Client;
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
		$client = new Client( WPKB_ALGOLIA_APP_ID, WPKB_ALGOLIA_API_KEY );
		$index = $client->initIndex( WPKB_ALGOLIA_INDEX_NAME );

		$batch_number = 1;
		$posts = array( '' );
		$site_url = get_site_url();

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
			$posts = array_map( function( WP_Post $post ) use( $site_url ){

				$categories = wp_get_post_terms( $post->ID, 'wpkb-category',
					array(
						'fields' => 'names'
					)
				);

				$keywords = wp_get_post_terms( $post->ID, 'wpkb-keyword',
					array(
						'fields' => 'names'
					)
				);


				$array = [
					'objectID' => $post->ID,
					'title' => $post->post_title,
					'content' => strip_tags( $post->post_content ),
					'categories' => $categories,
					'keywords' => $keywords,
					'created' => $post->post_date_gmt,
					'updated' => $post->post_modified_gmt,
					'path' => str_replace( $site_url, '', get_permalink( $post->ID ) )
				];


				// @todo take wpkb_rating into account
				return $array;
			}, $posts );

			$index->addObjects( $posts );
			$batch_number++;
		}

	}

}