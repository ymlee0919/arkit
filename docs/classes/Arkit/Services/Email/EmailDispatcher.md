***

# EmailDispatcher

Email dispatcher (Under construction)



* Full name: `\Arkit\Services\Email\EmailDispatcher`




## Methods


### __construct



```php
public __construct(): mixed
```












***

### connect



```php
public connect(array& $config): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### setFrom



```php
public setFrom(string $email, string|null $name = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$email` | **string** |  |
| `$name` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../../../PHPMailer/PHPMailer/Exception.md)



***

### addDestination



```php
public addDestination(string $email, string|null $name = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$email` | **string** |  |
| `$name` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../../../PHPMailer/PHPMailer/Exception.md)



***

### addCC



```php
public addCC(string $email, string|null $name = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$email` | **string** |  |
| `$name` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../../../PHPMailer/PHPMailer/Exception.md)



***

### setMessage

Set the content of the message

```php
public setMessage(string $subject, string $message, string|null $summary = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$subject` | **string** |  |
| `$message` | **string** |  |
| `$summary` | **string&#124;null** |  |





***

### addAttachment

Add and attachment

```php
public addAttachment(string $filePath, ?string $fileName = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filePath` | **string** | Full file path |
| `$fileName` | **?string** | File name inside the email |




**Throws:**

- [`Exception`](../../../PHPMailer/PHPMailer/Exception.md)



***

### dispatch

Dispatch the message

```php
public dispatch(): bool
```












***

### release

Release the connection

```php
public release(): void
```












***

### getError

Get the last error reported

```php
public getError(): string
```












***


***
> Automatically generated on 2023-12-15
