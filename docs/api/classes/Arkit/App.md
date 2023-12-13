***

# App

Class Application
Manage the application



* Full name: `\Arkit\App`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### store

Array to store global values

```php
public static ?array $store
```



* This property is **static**.


***

### config

Application configuration

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

Request

```php
public static ?\Arkit\Core\HTTP\RequestInterface $Request
```



* This property is **static**.


***

### Response

Output

```php
public static ?\Arkit\Core\HTTP\Response $Response
```



* This property is **static**.


***

### Cache

Cache manager

```php
public static ?\Arkit\Core\Persistence\Cache\CacheInterface $Cache
```



* This property is **static**.


***

### InputValidator

Form validator

```php
public static ?\Arkit\Core\Filter\InputValidator $InputValidator
```



* This property is **static**.


***

### Model

Model

```php
public static ?\Arkit\Core\Persistence\Database\Model $Model
```



* This property is **static**.


***

### Router

Router

```php
public static ?\Arkit\Core\Control\Routing\RouterInterface $Router
```



* This property is **static**.


***

### Logs

Logs manager

```php
public static ?\Arkit\Core\Monitor\Logger $Logs
```



* This property is **static**.


***

### Session

Session manager

```php
public static ?\Arkit\Core\Persistence\Server\Session $Session
```



* This property is **static**.


***

### Crypt

Crypt manager

```php
public static ?\Arkit\Core\Security\Crypt\CryptInterface $Crypt
```



* This property is **static**.


***

### Env

Environment vars manager

```php
public static ?\Arkit\Core\Config\DotEnv $Env
```



* This property is **static**.


***

## Methods


### __destruct



```php
public __destruct(): mixed
```












***

### getInstance



```php
public static getInstance(): mixed
```



* This method is **static**.








***

### init



```php
public init(): void
```











**Throws:**

- [`Exception`](../Exception.md)



***

### dispatch



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



```php
public static getRouter(string& $path): ?\Arkit\Core\Control\Routing\RouterInterface
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** |  |




**Throws:**

- [`Exception`](../Exception.md)



***

### loadInputValidator



```php
public static loadInputValidator(): void
```



* This method is **static**.







**Throws:**

- [`Exception`](../Exception.md)



***

### startSession

Start the session

```php
public static startSession(): void
```



* This method is **static**.








***

### loadModel

Load the model

```php
public static loadModel(string|null $modelName): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$modelName` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../Exception.md)



***

### readConfig



```php
public static readConfig(string $path): array
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** |  |





***

### fullPath



```php
public static fullPath(string $relPath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$relPath` | **string** |  |





***

### fullPathFromSystem



```php
public static fullPathFromSystem(string $relPath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$relPath` | **string** |  |





***


***
> Automatically generated on 2023-12-13
