***

# DotEnv

Environment-specific configuration handler.

Taken form https://github.com/vlucas/phpdotenv

* Full name: `\Arkit\Core\Config\DotEnv`
* This class implements:
[`\ArrayAccess`](../../../ArrayAccess.md)




## Methods


### __construct

Constructor of the class

```php
public __construct(string $path, string $file = &#039;.env&#039;): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | Absolute path to environment configuration file |
| `$file` | **string** | File name (default .env) |





***

### init

The main entry point, will load the .env file and process it
so that we end up with all settings in the PHP environment vars
(i.e. getenv(), $_ENV, and $_SERVER)

```php
public init(): bool
```












***

### offsetExists

Override ArrayAccess::offsetExists method.

```php
public offsetExists(mixed $offset): bool
```

This method is executed when using isset() or empty() on objects implementing ArrayAccess.






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** | An offset to check for |





***

### offsetGet

Override ArrayAccess::offsetGet method.

```php
public offsetGet(mixed $offset): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** | Offset to retrieve |





***

### offsetSet

Override ArrayAccess::offsetSet method.

```php
public offsetSet(mixed $offset, mixed $value): void
```

Assign a value to the specified offset.






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** | The offset to assign the value to |
| `$value` | **mixed** | The value to set |





***

### offsetUnset

Override ArrayAccess::offsetUnset method.

```php
public offsetUnset(mixed $offset): void
```

Unsets an offset






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** | The offset to unset |





***


***
> Automatically generated on 2023-12-13
