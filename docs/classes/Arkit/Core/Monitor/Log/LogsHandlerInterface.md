***

# LogsHandlerInterface





* Full name: `\Arkit\Core\Monitor\Log\LogsHandlerInterface`



## Methods


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
| `$request` | **\Arkit\Core\HTTP\RequestInterface** |  |





***

### registerLog

Register an internal event

```php
public registerLog(string $logType, string $message, array|null $context = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logType` | **string** |  |
| `$message` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### registerError

Register a critical error into the application

```php
public registerError(string $errorType, string $message, string $file, int $line, mixed& $backtrace): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** |  |
| `$message` | **string** |  |
| `$file` | **string** |  |
| `$line` | **int** |  |
| `$backtrace` | **mixed** |  |





***


***
> Automatically generated on 2023-12-13
