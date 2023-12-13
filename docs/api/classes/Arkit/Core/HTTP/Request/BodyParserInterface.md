***

# BodyParserInterface





* Full name: `\Arkit\Core\HTTP\Request\BodyParserInterface`
* This class is an **Abstract class**



## Properties


### values



```php
protected array $values
```






***

### headers



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



```php
public get(string $paramName): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramName` | **string** |  |





***

### getAll



```php
public getAll(): array
```












***

### exists



```php
public exists(string $paramsName): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramsName` | **string** |  |





***

### parse



```php
public parse(string $bodyContent): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bodyContent` | **string** | Request body content |





***


***
> Automatically generated on 2023-12-13
