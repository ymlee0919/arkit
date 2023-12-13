***

# RequestInterface

Class RequestInterface



* Full name: `\Arkit\Core\HTTP\RequestInterface`



## Methods


### init



```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### setBodyParser

Set parser for the payload request

```php
public setBodyParser(\Arkit\Core\HTTP\Request\BodyParserInterface $bodyParser): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bodyParser` | **\Arkit\Core\HTTP\Request\BodyParserInterface** |  |





***

### getHeader

Get value of header given the name

```php
public getHeader(string $headerName): string|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headerName` | **string** |  |





***

### getAllHeaders

Get all headers

```php
public getAllHeaders(): array
```












***

### validate

Validate the request given some rules

```php
public validate(): bool
```












***

### processBody



```php
public processBody(): void
```












***

### isValid

Check if the url is valid

```php
public isValid(): bool
```












***

### isEmptyUrl

Check if the url is emply (have not levels)

```php
public isEmptyUrl(): bool
```












***

### getUrlLevel

Get the level of url given an 1-based index

```php
public getUrlLevel(int $level): string|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$level` | **int** |  |





***

### getUrlLevels

Get an array of the url levels

```php
public getUrlLevels(): array
```












***

### getAllUrlParams

Get all parameters passed by url

```php
public getAllUrlParams(): array
```












***

### getUrlParam

Get the value of a parameter passed by url

```php
public getUrlParam(string $option): string|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **string** |  |





***

### getAllPostParams

Get all fields sent by post

```php
public getAllPostParams(): array
```












***

### getPostParam

Get a post value given the name

```php
public getPostParam(string $param): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **string** |  |





***

### isSetPostParam

Check if a post value was sent

```php
public isSetPostParam(string $paramName): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramName` | **string** |  |





***

### getFileParam

Get a post value given the name

```php
public getFileParam(string $param): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **string** |  |





***

### isSetFileParam

Check if a post value was sent

```php
public isSetFileParam(string $paramName): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramName` | **string** |  |





***

### getCookies



```php
public getCookies(): \Arkit\Core\Persistence\Client\CookieStore
```












***

### getRequestMethod

Get the requested method

```php
public getRequestMethod(): string
```












***

### getRequestUrl

Get the requested url

```php
public getRequestUrl(): null|string
```












***

### getRequestedDomain



```php
public getRequestedDomain(): string
```












***

### getRequestedProtocolAndDomain



```php
public getRequestedProtocolAndDomain(): string
```












***

### isAJAX

Test to see if a request contains the HTTP_X_REQUESTED_WITH header.

```php
public isAJAX(): bool
```












***

### isSecure

Attempts to detect if the current connection is secure through
a few different methods.

```php
public isSecure(): bool
```












***


***
> Automatically generated on 2023-12-13
