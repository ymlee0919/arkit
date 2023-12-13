***

# CookieStore





* Full name: `\Arkit\Core\Persistence\Client\CookieStore`



## Properties


### cookies

The cookie collection.

```php
protected array&lt;string,\Arkit\Core\Persistence\Client\Cookie&gt; $cookies
```






***

## Methods


### fromCookieHeaders

Creates a CookieStore from an array of `Set-Cookie` headers.

```php
public static fromCookieHeaders(string[] $headers): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headers` | **string[]** |  |





***

### fromServerRequest



```php
public static fromServerRequest(string $skippingList): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$skippingList` | **string** |  |





***

### __construct



```php
public __construct(\Arkit\Core\Persistence\Client\Cookie[] $cookies = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cookies` | **\Arkit\Core\Persistence\Client\Cookie[]** |  |





***

### has

Checks if a `Cookie` object identified by name is present in the collection.

```php
public has(string $name, ?string $value = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | The name of the cookie to search |
| `$value` | **?string** | (Optional) The value of the cookie |





***

### get

Retrieves an instance of `Cookie` identified by a name.

```php
public get(string $name): ?\Arkit\Core\Persistence\Client\Cookie
```

Return null if not find it






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | The name of the cookie to search |





***

### put

Store a new cookie and return a new collection. The original collection
is left unchanged.

```php
public put(\Arkit\Core\Persistence\Client\Cookie $cookie): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cookie` | **\Arkit\Core\Persistence\Client\Cookie** |  |





***

### remove

Removes a cookie from a collection and returns an updated collection.

```php
public remove(string $name): self
```

The original collection is left unchanged.

Removing a cookie from the store **DOES NOT** delete it from the browser.
If you intend to delete a cookie *from the browser*, you must put an empty
value cookie with the same name to the store.






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### removeFromBrowser

Set the value to empty and the expiration in the pass for deleting

```php
public removeFromBrowser(string $name): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### dispatch

Dispatches all cookies in store.

```php
public dispatch(): void
```












***

### clear

Clears the cookie collection.

```php
public clear(): void
```












***


***
> Automatically generated on 2023-12-13
