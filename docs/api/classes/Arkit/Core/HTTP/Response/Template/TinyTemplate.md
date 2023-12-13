***

# TinyTemplate

Class TinyTemplate



* Full name: `\Arkit\Core\HTTP\Response\Template\TinyTemplate`




## Methods


### __construct



```php
public __construct(string $templateFolder): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$templateFolder` | **string** |  |





***

### setDelimiters



```php
public setDelimiters(string $left, string $right): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$left` | **string** |  |
| `$right` | **string** |  |





***

### assign



```php
public assign(string $fieldName, mixed $value, bool $encodeFirst = true, bool $toUtf8 = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** |  |
| `$value` | **mixed** |  |
| `$encodeFirst` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### fetch

Compile a small template, generally used by emails

```php
public fetch(string $sourceTpl): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$sourceTpl` | **string** |  |





***


***
> Automatically generated on 2023-12-13
