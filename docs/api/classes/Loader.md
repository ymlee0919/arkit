***

# Loader





* Full name: `\Loader`



## Properties


### prefixes

An associative array where the key is a namespace prefix and the value
is an array of base directories for classes in that namespace.

```php
protected array $prefixes
```






***

## Methods


### getInstance



```php
public static getInstance(): \Loader
```



* This method is **static**.








***

### register

Register loader with SPL autoloader stack.

```php
public register(): void
```












***

### addNamespace

Adds a base directory for a namespace prefix.

```php
public addNamespace(string $prefix, string $base_dir, bool $prepend = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$prefix` | **string** | The namespace prefix. |
| `$base_dir` | **string** | A base directory for class files in the<br />namespace. |
| `$prepend` | **bool** | If true, prepend the base directory to the stack<br />instead of appending it; this causes it to be searched first rather<br />than last. |





***

### loadClass

Loads the class file for a given class name.

```php
public loadClass(string $class): string|bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$class` | **string** | The fully-qualified class name. |


**Return Value:**

The mapped file name on success, or boolean false on
failure.




***

### loadMappedFile

Load the mapped file for a namespace prefix and relative class.

```php
protected loadMappedFile(string $prefix, string $relative_class): string|bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$prefix` | **string** | The namespace prefix. |
| `$relative_class` | **string** | The relative class name. |


**Return Value:**

Boolean false if no mapped file can be loaded, or the
name of the mapped file that was loaded.




***

### requireFile

If a file exists, require it from the file system.

```php
protected requireFile(string $file): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$file` | **string** | The file to require. |


**Return Value:**

True if the file exists, false if not.




***

### import



```php
public static import(?string $className, string $lib, bool $include = false): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$className` | **?string** |  |
| `$lib` | **string** |  |
| `$include` | **bool** |  |





***

### loadDependencies



```php
public loadDependencies(): mixed
```












***


***
> Automatically generated on 2023-12-13
