***

# DotEnv

Environment-specific configuration



* Full name: `\Arkit\Core\Config\DotEnv`
* This class implements:
[`\ArrayAccess`](../../../ArrayAccess.md)



## Properties


### path

The directory where the .env file can be located.

```php
protected string $path
```






***

## Methods


### __construct

Builds the path to our file.

```php
public __construct(string $path, string $file = &#039;.env&#039;): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** |  |
| `$file` | **string** |  |





***

### init

The main entry point, will load the .env file and process it
so that we end up with all settings in the PHP environment vars
(i.e. getenv(), $_ENV, and $_SERVER)

```php
public init(): bool
```












***

### parse

Parse the .env file into an array of key => value

```php
protected parse(): ?array
```












***

### setVariable

Sets the variable into the environment. Will parse the string
first to look for {name}={value} pattern, ensure that nested
variables are handled, and strip it of single and double quotes.

```php
protected setVariable(string $name, string $value = &#039;&#039;): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$value` | **string** |  |





***

### normaliseVariable

Parses for assignment, cleans the $name and $value, and ensures
that nested variables are handled.

```php
protected normaliseVariable(string $name, string $value = &#039;&#039;): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$value` | **string** |  |





***

### sanitizeValue

Strips quotes from the environment variable value.

```php
protected sanitizeValue(string $value): string
```

This was borrowed from the excellent phpdotenv with very few changes.
https://github.com/vlucas/phpdotenv






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)



***

### resolveNestedVariables

Resolve the nested variables.

```php
protected resolveNestedVariables(string $value): string
```

Look for ${varname} patterns in the variable value and replace with an existing
environment variable.

This was borrowed from the excellent phpdotenv with very few changes.
https://github.com/vlucas/phpdotenv






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string** |  |





***

### getVariable

Search the different places for environment variables and return first value found.

```php
protected getVariable(string $name): string|null
```

This was borrowed from the excellent phpdotenv with very few changes.
https://github.com/vlucas/phpdotenv






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### offsetExists



```php
public offsetExists(mixed $offset): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetGet



```php
public offsetGet(mixed $offset): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetSet



```php
public offsetSet(mixed $offset, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |
| `$value` | **mixed** |  |





***

### offsetUnset



```php
public offsetUnset(mixed $offset): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***


***
> Automatically generated on 2023-12-13
