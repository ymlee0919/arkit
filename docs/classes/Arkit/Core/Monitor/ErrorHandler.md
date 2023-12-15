***

# ErrorHandler

Class to handle internar errors of the application



* Full name: `\Arkit\Core\Monitor\ErrorHandler`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### init

Start the error handler. Store the previous error_reporting handler.

```php
public static init(): void
```

It is initially called by \Arkit\App class.
It can be called after stop handle error by this class.

* This method is **static**.








***

### stop

Stop current error handler. Restore the previous error handler.

```php
public static stop(): void
```



* This method is **static**.








***

### onInternalServerError

Set a function that handle an Internal Server Error

```php
public static onInternalServerError(\Arkit\Core\Base\FunctionAddress $onError): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onError` | **\Arkit\Core\Base\FunctionAddress** | Function to handler an internal server error |





***

### handleServerError

Function to handle a Server Error

```php
public static handleServerError(int|string $type, string $message, string $file, int $line, mixed $trace): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **int&#124;string** | Error type |
| `$message` | **string** | Error message |
| `$file` | **string** | File where the error occured |
| `$line` | **int** | Line where the error occurred |
| `$trace` | **mixed** | Callstack |





***

### handleException

Handle an exception

```php
public static handleException(\Exception|\Error $exception): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$exception` | **\Exception&#124;\Error** |  |





***

### showInternalServerError

Display internal server error message. Show a default message when onInternalServerError was not set.

```php
public static showInternalServerError(): void
```



* This method is **static**.








***


***
> Automatically generated on 2023-12-15
