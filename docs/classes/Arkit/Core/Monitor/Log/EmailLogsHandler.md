***

# EmailLogsHandler

Logs handler using email



* Full name: `\Arkit\Core\Monitor\Log\EmailLogsHandler`
* This class implements:
[`\Arkit\Core\Monitor\Log\LogsHandlerInterface`](./LogsHandlerInterface.md)




## Methods


### __construct

Constructor of the class

```php
public __construct(array& $config): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Configuration |





***

### init

Initialize the handler

```php
public init(): void
```












***

### registerRequest

Register a request made to the application

```php
public registerRequest(\Arkit\Core\HTTP\RequestInterface& $request): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** | Request to be registered |





***

### registerLog

Register an internal event

```php
public registerLog(string $logType, string $message, ?array $context = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logType` | **string** | Log type |
| `$message` | **string** | Message to register |
| `$context` | **?array** | Callstack |





***

### registerError

Register a critical error into the application

```php
public registerError(string $errorType, string $message, string $file, int $line, mixed& $backtrace): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** | Type of error |
| `$message` | **string** | Error message |
| `$file` | **string** | File where the error occurred |
| `$line` | **int** | Line where the error occurred |
| `$backtrace` | **mixed** | Callstack |





***


***
> Automatically generated on 2023-12-15
