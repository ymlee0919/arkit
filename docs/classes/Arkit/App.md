***

# App

Application controller class. Implements the singleton pattern.



* Full name: `\Arkit\App`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### store

Array to store global values. It can be used to share values between objects.

```php
public static ?array $store
```



* This property is **static**.


***

### config

Store the global application configuration

```php
public static ?array $config
```



* This property is **static**.


***

### ROOT_DIR

Application root directory

```php
public static ?string $ROOT_DIR
```



* This property is **static**.


***

### Request

Request handler

```php
public static ?\Arkit\Core\HTTP\RequestInterface $Request
```



* This property is **static**.


***

### Response

Response handler

```php
public static ?\Arkit\Core\HTTP\Response $Response
```



* This property is **static**.


***

### Cache

Cache handler

```php
public static ?\Arkit\Core\Persistence\Cache\CacheInterface $Cache
```



* This property is **static**.


***

### InputValidator

Form input validator

```php
public static ?\Arkit\Core\Filter\InputValidator $InputValidator
```



* This property is **static**.


***

### Model

Business model class

```php
public static ?\Arkit\Core\Persistence\Database\Model $Model
```



* This property is **static**.


***

### Router

Router for url

```php
public static ?\Arkit\Core\Control\Routing\RouterInterface $Router
```



* This property is **static**.


***

### Logs

Logs handler

```php
public static ?\Arkit\Core\Monitor\Logger $Logs
```



* This property is **static**.


***

### Session

Session vars handler

```php
public static ?\Arkit\Core\Persistence\Server\Session $Session
```



* This property is **static**.


***

### Crypt

Cryptography algorithms provider

```php
public static ?\Arkit\Core\Security\Crypt\CryptInterface $Crypt
```



* This property is **static**.


***

### Env

Environment vars handler

```php
public static ?\Arkit\Core\Config\DotEnv $Env
```



* This property is **static**.

**See Also:**

* \Arkit\Core\Config\DotEnv - 

***

## Methods


### __destruct



```php
public __destruct(): mixed
```












***

### getInstance

Return the unique instance of the class

```php
public static getInstance(): \Arkit\App
```



* This method is **static**.








***

### init

Init the application

```php
public init(): void
```











**Throws:**

- [`Exception`](../Exception.md)



***

### dispatch

Dispatch a giver request. This method in invoqued automatically by index.php

```php
public dispatch(\Arkit\Core\HTTP\RequestInterface& $request): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** |  |




**Throws:**

- [`Exception`](../Exception.md)



***

### getRouter

Return the Url Router given the path

```php
public static getRouter(string& $path): ?\Arkit\Core\Control\Routing\RouterInterface
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | Absolute path to the file router definition |


**Return Value:**

Url router



**Throws:**

- [`Exception`](../Exception.md)



***

### loadInputValidator

Load the form input validator.

```php
public static loadInputValidator(): void
```



* This method is **static**.







**Throws:**

- [`Exception`](../Exception.md)



***

### startSession

Start the session handler. It must be called before use any session var.

```php
public static startSession(): void
```



* This method is **static**.








***

### loadModel

Load the model to work with.

```php
public static loadModel(string|null $modelName): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$modelName` | **string&#124;null** | Name of the model to be loaded. If not set, the model will be taken form the configuration |




**Throws:**

- [`Exception`](../Exception.md)



***

### readConfig

Read a yaml file from an absolute path

```php
public static readConfig(string $path): array
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | Absolute path of the file |


**Return Value:**

Configuration




***

### fullPath

Return absolute path form a relative path. Relative path is taken form the current working directory

```php
public static fullPath(string $relPath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$relPath` | **string** | Relative path |


**Return Value:**

Absolute path form a relative path




***

### fullPathFromSystem

Return absolute path from a relative path inside the active System directory.

```php
public static fullPathFromSystem(string $relPath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$relPath` | **string** | Relative path |


**Return Value:**

Absolute path from a relative path inside the active System directory




***


***
> Automatically generated on 2023-12-15
