***

# MemcacheCacheEngine

Cache engine over Memcache



* Full name: `\Arkit\Core\Persistence\Cache\MemcacheCacheEngine`
* This class implements:
[`\Arkit\Core\Persistence\Cache\CacheInterface`](./CacheInterface.md)




## Methods


### init

Init the cache engine

```php
public init(array& $config): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration |





***

### set

Set a value under a key

```php
public set(string $key, mixed $value, int $expire = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |
| `$value` | **mixed** |  |
| `$expire` | **int** |  |





***

### get

Get a value under a key

```php
public get(string $key): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Index |





***

### remove

Remove the value under a key

```php
public remove(string $key): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Index to remove |





***

### clean

Remove all values

```php
public clean(): bool
```












***

### getLastError

Return the last error occurred

```php
public getLastError(): string
```












***

### isEnabled

Return if is enabled after initialization

```php
public isEnabled(): bool
```












***


***
> Automatically generated on 2023-12-15
