<?php

namespace WPKB\Algolia;

use WPKB\Plugin;
use WP_Post;

class Searcher {

	/**
	 * Init
	 */
	public static function init() {
		$instance = new self;
		$instance->add_hooks();
		return $instance;
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_filter( 'wpkb_get_search_results', array( $this, 'search' ), 10, 2 );
	}

	/**
	 * @param $results
	 * @param $query
	 *
	 * @return array
	 */
	public function search( $results, $query ) {

		$index = $index = Client::initIndex( WPKB_ALGOLIA_INDEX_NAME );
		$result = $index->search( $query );

		if( ! is_array( $result ) ) {
			return array();
		}

		$posts = array();

		// turn algolia results into WP_Post object
		foreach( $result['hits'] as $hit )  {
			$post = get_post( $hit['objectID'] );
			if( $post instanceof WP_Post ) {
				$post->post_title = $hit['_highlightResult']['title']['value'];
				$posts[] = $post;
			}
		}

		return $posts;
	}

}