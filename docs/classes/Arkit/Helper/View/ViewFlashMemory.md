***

# ViewFlashMemory

Class to store flash values in session



* Full name: `\Arkit\Helper\View\ViewFlashMemory`




## Methods


### __construct



```php
public __construct(string $url): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** |  |





***

### storeInputError

Store an input error

```php
public storeInputError(string $fieldName, string $error): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** |  |
| `$error` | **string** |  |





***

### storeInputErrors

Store a list of input errors

```php
public storeInputErrors(array $errorList): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorList` | **array** |  |





***

### storeSuccessMessage

Store the success message

```php
public storeSuccessMessage(string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |





***

### storeWarning

Store the warning

```php
public storeWarning(string $warning): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$warning` | **string** |  |





***

### storeCustomError

Store other error types, for internals and others

```php
public storeCustomError(string $errorType, string $error): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** |  |
| `$error` | **string** |  |





***

### storeCustomValue

Store custom values, useful for inserted form values

```php
public storeCustomValue(string $indexName, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$indexName` | **string** |  |
| `$value` | **mixed** |  |





***

### sendToResponse



```php
public sendToResponse(): void
```












***


***
> Automatically generated on 2023-12-13
