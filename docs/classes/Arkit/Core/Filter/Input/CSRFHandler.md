***

# CSRFHandler

This class handle the CSRF token.

- Generate and validate a hidden input code given a Form ID and expiration time
- Generate and validate a cookie given a Form ID.
   The cookie name is associated with the Form ID. So, the unique who know the expected cookie is the server
   The cookie value is associated with the Session and the Form ID.

* Full name: `\Arkit\Core\Filter\Input\CSRFHandler`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`CSRF_VALIDATION_INVALID`|public| |&#039;INVALID&#039;|
|`CSRF_VALIDATION_EXPIRED`|public| |&#039;EXPIRED&#039;|
|`CSRF_VALIDATION_SUCCESS`|public| |&#039;SUCCESS&#039;|


## Methods


### __construct



```php
public __construct(): mixed
```












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

### generateCode



```php
public generateCode(string $formId, int|null $expire = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$expire` | **int&#124;null** |  |





***

### generateCookie



```php
public generateCookie(string $formId, ?int $expire = null, string $path = &#039;/&#039;): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$expire` | **?int** |  |
| `$path` | **string** |  |





***

### validateCode



```php
public validateCode(string $formId, string $code): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$code` | **string** |  |





***

### validateCookie



```php
public validateCookie(string $formId): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |





***

### releaseCookie



```php
public releaseCookie(string $formId): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |





***


***
> Automatically generated on 2023-12-13
