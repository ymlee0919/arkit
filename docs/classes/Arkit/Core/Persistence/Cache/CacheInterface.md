***

# CacheInterface

Interface to define a Cache engine



* Full name: `\Arkit\Core\Persistence\Cache\CacheInterface`



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
public set(string $key, mixed $value, int|null $expire = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |
| `$value` | **mixed** |  |
| `$expire` | **int&#124;null** |  |





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

### isEnabled

Return if is enabled after initialization

```php
public isEnabled(): bool
```












***

### getLastError

Return the last error occurred

```php
public getLastError(): string
```












***


***
> Automatically generated on 2023-12-15
