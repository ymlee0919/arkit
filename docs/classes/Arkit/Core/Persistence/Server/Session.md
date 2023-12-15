***

# Session

Session variables manager. Implements the singleton pattern.



* Full name: `\Arkit\Core\Persistence\Server\Session`
* This class implements:
[`\ArrayAccess`](../../../../ArrayAccess.md)




## Methods


### getInstance

Return the unique instance of the class

```php
public static getInstance(): \Arkit\Core\Persistence\Server\Session
```



* This method is **static**.








***

### init

Initialize internal variables

```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Array of configurations |





***

### start

Star the session

```php
public start(): void
```












***

### getCryptKey

Get internal crypted key for the current session.

```php
public getCryptKey(): string
```












***

### get

Get a session var given the key

```php
public get(string $key): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |





***

### set

Set a session var

```php
public set(string $key, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Key to search for. The key ID is not allowed |
| `$value` | **mixed** | Value to store |





***

### remove

Remove a session var

```php
public remove(string $key): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Key var to remove |





***

### setFlash

Set a var session like flash, they will persist until the next call, then
they will be deleted

```php
public setFlash(string $key, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Key |
| `$value` | **mixed** | Value to store |





***

### makeFlash

Make a stored var as flash

```php
public makeFlash(string $key): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |





***

### pop

Get a session value given the key and remove it

```php
public pop(string $key): mixed|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Session key to pop |





***

### regenerate

Regenerate the session

```php
public regenerate(bool $removeOld = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$removeOld` | **bool** |  |





***

### destroy

Destroy the current session

```php
public destroy(): void
```












***

### load

Load some options

```php
public load(array $options): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** | Options of session_start function |





***

### id

Return the current session Id

```php
public id(): string
```












***

### is_set

Check if a session var is defined

```php
public is_set(string $key): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Index |





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
> Automatically generated on 2023-12-15
