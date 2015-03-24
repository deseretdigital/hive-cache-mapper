# hive-cache-mapper

Hive Cache Mapper is a tool for caching API responses and storing a map of references to related objects.

## Configuration

Configuration is done on instantiation of the `CacheMapper` object. This passes any configuration options through to
a caching client, so it should match whatever. It uses `Client\RedisClient` by default, which uses Predis for Redis
interaction.

## Example

*Update cache*

```php
$cacheData = []; // cache
$cacheMapper = new CacheMapper();
$cacheMapper->keyPrefix = 'example.cache.';
$cacheMapper->cache('page:01',json_encode($cacheData)); // JSON encoded string
```

*Retrieve map entries*

```php
$cacheMapper = new CacheMapper();
$cacheMapper->keyPrefix = 'example.cache.';
$mapEntries = $cacheMapper->getMapEntries('page:01');
```

Result

```json
{
    "contentprofiles": ['id_a', 'id_b'],
    "pages": ['id_c', 'id_d']
}
```

Build Testing Incrementor: 1
