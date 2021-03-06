<?php


namespace WPKB\Algolia;

use WP_Post;

class Helper {

	/**
	 * @var string
	 */
	public $site_url = '';

	/**
	 *
	 */
	public function __construct() {
		$this->site_url = get_option('home');
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function post_for_index( WP_Post $post ) {
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

		$permalink = get_permalink( $post->ID );

		// strip HTML tags (but keep content)
		$content = strip_tags( $post->post_content );

		// strip shortcodes (but keep content)
		$content = preg_replace( '/\[(\/)?[^\]]+\]/', '', $content );

		// what we send to Algolia
		$array = [
			'objectID' => $post->ID,
			'title' => $post->post_title,
			'content' => $content,
			'categories' => $categories,
			'keywords' => $keywords,
			'created' => $post->post_date_gmt,
			'updated' => $post->post_modified_gmt,
			'path' => str_replace( $this->site_url, '', $permalink ),
			'url' => $permalink,
		];

		// TODO: take wpkb_rating into account
		return $array;
	}
}
