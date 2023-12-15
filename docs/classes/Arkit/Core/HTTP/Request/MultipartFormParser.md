***

# MultipartFormParser

Parser of request payload in MultipartForm format



* Full name: `\Arkit\Core\HTTP\Request\MultipartFormParser`
* Parent class: [`\Arkit\Core\HTTP\Request\PayloadParserInterface`](./PayloadParserInterface.md)




## Methods


### parse

Method to parse con content of the request payload

```php
public parse(string $bodyContent): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bodyContent` | **string** | Request payload content |





***


## Inherited methods


### __construct

Constructor of the class

```php
public __construct(): mixed
```












***

### setHeaders

Set request headers

```php
public setHeaders(array $headers): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headers` | **array** |  |





***

### get

Get a value given an index

```php
public get(string $paramName): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramName` | **string** | Index |





***

### getAll

Get all values of the request

```php
public getAll(): array
```












***

### exists

Validate if an index exists

```php
public exists(string $paramsName): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramsName` | **string** | Index |





***

### parse

Method to parse con content of the request payload

```php
public parse(string $bodyContent): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bodyContent` | **string** | Request payload content |





***


***
> Automatically generated on 2023-12-15
