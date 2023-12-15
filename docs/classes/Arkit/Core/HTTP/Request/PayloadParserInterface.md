***

# PayloadParserInterface

Interface to define a class for parse the http request payload



* Full name: `\Arkit\Core\HTTP\Request\PayloadParserInterface`
* This class is an **Abstract class**



## Properties


### values

Array of values

```php
protected array $values
```






***

### headers

Array of headers

```php
protected ?array $headers
```






***

## Methods


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
