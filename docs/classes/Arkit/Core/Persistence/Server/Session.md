***

# Session

Class Session



* Full name: `\Arkit\Core\Persistence\Server\Session`
* This class implements:
[`\ArrayAccess`](../../../../ArrayAccess.md)




## Methods


### getInstance



```php
public static getInstance(): \Arkit\Core\Persistence\Server\Session
```



* This method is **static**.








***

### init



```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### start

Star the session

```php
public start(): void
```












***

### getCryptKey



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
| `$key` | **string** |  |





***

### regenerate



```php
public regenerate(bool $removeOld = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$removeOld` | **bool** |  |





***

### destroy



```php
public destroy(): void
```












***

### load



```php
public load(array $options): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **array** |  |





***

### id



```php
public id(): string
```












***

### is_set



```php
public is_set(string $key): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |





***

### offsetExists



```php
public offsetExists(mixed $offset): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetGet



```php
public offsetGet(mixed $offset): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetSet



```php
public offsetSet(mixed $offset, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |
| `$value` | **mixed** |  |





***

### offsetUnset



```php
public offsetUnset(mixed $offset): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***


***
> Automatically generated on 2023-12-13
