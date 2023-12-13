***

# UrlEncodedParser





* Full name: `\Arkit\Core\HTTP\Request\UrlEncodedParser`
* Parent class: [`\Arkit\Core\HTTP\Request\BodyParserInterface`](./BodyParserInterface.md)




## Methods


### parse



```php
public parse(string $bodyContent): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$bodyContent` | **string** | Request body content |





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
