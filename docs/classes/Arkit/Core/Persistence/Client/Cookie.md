***

# Cookie

Cookie handler class



* Full name: `\Arkit\Core\Persistence\Client\Cookie`
* This class implements:
[`\Arkit\Core\Persistence\Client\CookieInterface`](./CookieInterface.md)



## Properties


### name



```php
protected string $name
```






***

### value



```php
protected string $value
```






***

### expires



```php
protected int $expires
```






***

### path



```php
protected string $path
```






***

### domain



```php
protected string $domain
```






***

### secure



```php
protected bool $secure
```






***

### httponly



```php
protected bool $httponly
```






***

### samesite



```php
protected string $samesite
```






***

### raw



```php
protected bool $raw
```






***

## Methods


### setDefaults

Set the default attributes to a Cookie instance

```php
public static setDefaults(array&lt;string,mixed&gt; $config = []): array&lt;string,mixed&gt;
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array<string,mixed>** |  |


**Return Value:**

The old defaults array. Useful for resetting.




***

### fromHeaderString

Create Cookie instance from "set-cookie" header string.

```php
public static fromHeaderString(string $cookie, array&lt;string,mixed&gt; $defaults = []): self|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cookie` | **string** | Cookie header string. |
| `$defaults` | **array<string,mixed>** | Default attributes. |





***

### create

Factory method to create Cookie instances.

```php
public static create(string $name, array|string $value, array&lt;string,mixed&gt; $options = []): ?\Arkit\Core\Persistence\Client\Cookie
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | Cookie name |
| `$value` | **array&#124;string** | Value of the cookie |
| `$options` | **array<string,mixed>** | Cookies options. |





***

### build

Constructor

```php
public static build(string $name, string $value = &#039;&#039;, ?int $expiresAt = null, string|null $path = null, string|null $domain = null, bool|null $secure = null, bool|null $httpOnly = null, string|null $sameSite = null): ?\Arkit\Core\Persistence\Client\Cookie
```

The constructors args are similar to the native PHP `setcookie()` method.
The only difference is the 3rd argument which excepts null or an
DateTime or DateTimeImmutable object instead an integer.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | Cookie name |
| `$value` | **string** | Value of the cookie |
| `$expiresAt` | **?int** | Expiration time and date |
| `$path` | **string&#124;null** | Path |
| `$domain` | **string&#124;null** | Domain |
| `$secure` | **bool&#124;null** | Is secure |
| `$httpOnly` | **bool&#124;null** | HTTP Only |
| `$sameSite` | **string&#124;null** | Samesite |





**See Also:**

* https://php.net/manual/en/function.setcookie.php - 

***

### isValidName

Validates the cookie name per RFC 2616.

```php
public static isValidName(string $name): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### isValidSameSite

Validates the `SameSite` to be within the allowed types.

```php
protected static isValidSameSite(string $samesite, bool $secure): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$samesite` | **string** |  |
| `$secure` | **bool** |  |





**See Also:**

* https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite - 

***

### convertExpiresTimestamp

Converts expires time to Unix format.

```php
protected static convertExpiresTimestamp(\DateTimeInterface|int|string $expires): int|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$expires` | **\DateTimeInterface&#124;int&#124;string** |  |





***

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

### getValue

Gets the cookie value.

```php
public getValue(): string
```












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












***

### isHTTPOnly

Gets the "HttpOnly" cookie attribute.

```php
public isHTTPOnly(): bool
```












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
