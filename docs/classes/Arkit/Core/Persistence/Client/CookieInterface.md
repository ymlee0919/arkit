***

# CookieInterface

Interface for a value object representation of an HTTP cookie.



* Full name: `\Arkit\Core\Persistence\Client\CookieInterface`

**See Also:**

* https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie - 


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`SAMESITE_NONE`|public| |&#039;none&#039;|
|`SAMESITE_LAX`|public| |&#039;lax&#039;|
|`SAMESITE_STRICT`|public| |&#039;strict&#039;|
|`ALLOWED_SAMESITE_VALUES`|public| |[self::SAMESITE_NONE, self::SAMESITE_LAX, self::SAMESITE_STRICT]|
|`EXPIRES_FORMAT`|public| |&#039;D, d-M-Y H:i:s T&#039;|

## Methods


### getId

Returns a unique identifier for the cookie consisting
of its prefixed name, path, and domain.

```php
public getId(): string
```












***

### getName

Gets the cookie name.

```php
public getName(): string
```












***

### getValue

Gets the cookie value.

```php
public getValue(): string
```












***

### setName

Gets the cookie name.

```php
public setName(string $name): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | The new name of the cookie |





***

### setValue

Gets the cookie value.

```php
public setValue(string $value): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string** | The new value of the cookie |





***

### getExpiresTimestamp

Gets the time in Unix timestamp the cookie expires.

```php
public getExpiresTimestamp(): int
```












***

### getExpiresString

Gets the formatted expires time.

```php
public getExpiresString(): string
```












***

### setExpired

Set the cookie expired

```php
public setExpired(): $this
```












***

### isExpired

Checks if the cookie is expired.

```php
public isExpired(): bool
```












***

### getMaxAge

Gets the "Max-Age" cookie attribute.

```php
public getMaxAge(): int
```












***

### getPath

Gets the "Path" cookie attribute.

```php
public getPath(): string
```












***

### setPath

Gets the "Path" cookie attribute.

```php
public setPath(string $path): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | The new path of the cookie |





***

### getDomain

Gets the "Domain" cookie attribute.

```php
public getDomain(): string
```












***

### isSecure

Gets the "Secure" cookie attribute.

```php
public isSecure(): bool
```

Checks if the cookie is only sent to the server when a request is made
with the `https:` scheme (except on `localhost`), and therefore is more
resistent to man-in-the-middle attacks.










***

### isHTTPOnly

Gets the "HttpOnly" cookie attribute.

```php
public isHTTPOnly(): bool
```

Checks if JavaScript is forbidden from accessing the cookie.










***

### getSameSite

Gets the "SameSite" cookie attribute.

```php
public getSameSite(): string
```












***

### getOptions

Gets the options that are passable to the `setcookie` variant
available on PHP 7.3+

```php
public getOptions(): array&lt;string,mixed&gt;
```












***

### toHeaderString

Returns the Cookie as a header value.

```php
public toHeaderString(): string
```












***

### __toString

Returns the string representation of the Cookie object.

```php
public __toString(): string
```












***

### toArray

Returns the array representation of the Cookie object.

```php
public toArray(): array&lt;string,mixed&gt;
```












***

### dispatch

Dispatch the cookie

```php
public dispatch(): void
```












***


***
> Automatically generated on 2023-12-15
