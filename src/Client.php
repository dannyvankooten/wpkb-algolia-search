<?php

namespace WPKB\Algolia;

use AlgoliaSearch;

class Client {

	/**
	 * @var
	 */
	protected static $instance;

	/**
	 * @param $method
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic( $method, $arguments ) {

		if( ! self::$instance instanceof AlgoliaSearch\Client ) {
			self::$instance = new AlgoliaSearch\Client( WPKB_ALGOLIA_APP_ID, WPKB_ALGOLIA_API_KEY );
		}

		return call_user_func_array( array( self::$instance, $method ), $arguments );
	}

}