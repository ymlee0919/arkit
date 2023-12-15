***

# TemplateDispatcher

Dispatch the response using a template engine



* Full name: `\Arkit\Core\HTTP\Response\TemplateDispatcher`
* This class implements:
[`\Arkit\Core\HTTP\Response\DispatcherInterface`](./DispatcherInterface.md)




## Methods


### __construct

Template directory

```php
public __construct(string|null $folderTemplate): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$folderTemplate` | **string&#124;null** |  |





***

### getTemplate

Return the template

```php
public getTemplate(): \Arkit\Core\HTTP\Response\Template\Template
```












***

### assignValues

Assign multiple values to the dispatcher

```php
public assignValues(array& $values): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$values` | **array** | VarName =&gt; Value array |





***

### assign

Assign a single value to the dispatcher

```php
public assign(string $varName, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$varName` | **string** | Name inside the response |
| `$value` | **mixed** | Value of the var |





***

### inputError

Set an input error associated to a field

```php
public inputError(string $fieldName, string $error): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** | Field of error |
| `$error` | **string** | Error description |





***

### inputErrors

Set a list of input errors

```php
public inputErrors(array $errors): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errors` | **array** | FieldName =&gt; ErrorMessage array |





***

### error

Set a custom error type

```php
public error(string $errorType, string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** | Error type |
| `$message` | **string** | Error message |





***

### warning

Set a warning

```php
public warning(string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | Warning message |





***

### success

Set a success message

```php
public success(string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | Success message |





***

### dispatch

Dispatch the content to the payload

```php
public dispatch(?string $resource, ?array $arguments = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$resource` | **?string** | Resource to dispatch |
| `$arguments` | **?array** | Arguments for dispatching |





***


***
> Automatically generated on 2023-12-15
