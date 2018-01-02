<?php

function wpkb_get_algolia_client() {
	return new \AlgoliaSearch\Client( WPKB_ALGOLIA_APP_ID, WPKB_ALGOLIA_API_KEY );
}

function wpkb_get_algolia_index() {
	$client = wpkb_get_algolia_client();
	return $client->initIndex( WPKB_ALGOLIA_INDEX_NAME );
}
