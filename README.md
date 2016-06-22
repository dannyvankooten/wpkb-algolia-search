WPKB Algolia Search
========

Keeps an [Algolia](https://algolia.com) index of all knowledge base articles and replaces the default search with Algolia powered search, which performs much better.

## Usage

Define the following constants in your `wp-config.php`.

```php
define( 'WPKB_ALGOLIA_APP_ID', '' );
define( 'WPKB_ALGOLIA_API_KEY', '' );
define( 'WPKB_ALGOLIA_INDEX_NAME', '' );
define( 'WPKB_ALGOLIA_API_KEY_SEARCH_ONLY', '' );
```

You can get these from [your Algolia api keys page](https://www.algolia.com/api-keys).

## License

GPL2