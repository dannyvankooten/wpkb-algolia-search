<?php

namespace WPKB\Algolia;

use WPKB\Plugin;

/**
 * Class IndexUpdater
 * @package WPKB\Algolia
 */
class IndexUpdater {

	/**
	 *
	 */
	public static function init() {
		$instance = new self;
		$instance->add_hooks();
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'save_post', array( $this, 'update_post_in_index' ), 10, 3 );
		add_action( 'trashed_post', array( $this, 'remove_post_from_index' ) );
	}

	/**
	 * Update index
	 *
	 * @param $post_id
	 * @param $post
	 * @param $is_existing_post
	 *
	 * @return bool
	 */
	public function update_post_in_index( $post_id, $post, $is_existing_post ) {

		if( $post->post_type !== Plugin::POST_TYPE_NAME ) {
			return false;
		}

		$helper = new Helper();
		$object = $helper->post_for_index( $post );

		$index = Client::initIndex( WPKB_ALGOLIA_INDEX_NAME );
		$index->addObject( $object, $post_id );

		return true;
	}

	/**
	 * @param $post_id
	 */
	public function remove_post_from_index( $post_id ) {
		$index = Client::initIndex( WPKB_ALGOLIA_INDEX_NAME );
		$index->deleteObject( $post_id );
	}

}