***

# ErrorHandler

Class Router



* Full name: `\Arkit\Core\Monitor\ErrorHandler`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### init



```php
public static init(): void
```



* This method is **static**.








***

### stop

Stop current error handler

```php
public static stop(): void
```



* This method is **static**.








***

### onInternalServerError



```php
public static onInternalServerError(\Arkit\Core\Base\FunctionAddress $onError): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onError` | **\Arkit\Core\Base\FunctionAddress** |  |





***

### handleServerError



```php
public static handleServerError(int|string $type, string $message, string $file, int $line, mixed $trace): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **int&#124;string** |  |
| `$message` | **string** |  |
| `$file` | **string** |  |
| `$line` | **int** |  |
| `$trace` | **mixed** |  |





***

### handleException



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



```php
public static showInternalServerError(): void
```



* This method is **static**.








***


***
> Automatically generated on 2023-12-13
