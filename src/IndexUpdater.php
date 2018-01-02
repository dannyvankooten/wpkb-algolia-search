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

		// only run for KB articles
		if( $post->post_type !== 'wpkb-article' ) {
			return false;
		}

		// only run for published posts
		if( $post->post_status !== 'publish' ) {
			return false;
		}

		// don't run for revisions or autosaves
		if( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
			return false;
		}

		$helper = new Helper();
		$object = $helper->post_for_index( $post );
		$index = wpkb_get_algolia_index();

		try{
			$index->addObject( $object, $post_id );
		} catch( \Exception $e ) {
			// ugh.. fail silently?
			return;
		}

		return true;
	}

	/**
	 * @param $post_id
	 */
	public function remove_post_from_index( $post_id ) {
		$index = wpkb_get_algolia_index();
		$index->deleteObject( $post_id );
	}

}
