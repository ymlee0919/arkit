***

# TinyTemplate

Tiny template engine. It only allow direct variables replacement.

It can be used not only for html format.

* Full name: `\Arkit\Core\HTTP\Response\Template\TinyTemplate`




## Methods


### __construct

Constructor of the class

```php
public __construct(string $templateFolder): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$templateFolder` | **string** | Absolute path of the location of the template. |





***

### setDelimiters

Change delimiters used into the template

```php
public setDelimiters(string $left, string $right): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$left` | **string** | Left delimiter |
| `$right` | **string** | Right delimiter |





***

### assign

Assign a value to the template

```php
public assign(string $fieldName, mixed $value, bool $encodeFirst = true, bool $toUtf8 = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** | Name of the variable of the template |
| `$value` | **mixed** | Assigned value |
| `$encodeFirst` | **bool** | Encode to html entities before compile the template |
| `$toUtf8` | **bool** | Encode to utf-8. Only works if $encodeFirst is true |





***

### fetch

Compile a small template

```php
public fetch(string $sourceTpl): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$sourceTpl` | **string** | Name of the template file |


**Return Value:**

Compiled template




***


***
> Automatically generated on 2023-12-15
