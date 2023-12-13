***

# Logger

Class LogsManager



* Full name: `\Arkit\Core\Monitor\Logger`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### __construct

Constructor of the class

```php
public __construct(array& $config): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### init



```php
public init(): void
```












***

### setHandler



```php
public setHandler(\Arkit\Core\Monitor\Log\LogsHandlerInterface $handler, array $logTypes): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$handler` | **\Arkit\Core\Monitor\Log\LogsHandlerInterface** |  |
| `$logTypes` | **array** |  |





***

### logRequest



```php
public logRequest(\Arkit\Core\HTTP\RequestInterface& $request): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** |  |





***

### log



```php
public log(string $eventType, string $message, array|null $context = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$eventType` | **string** |  |
| `$message` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### debug

Detailed debug information.

```php
public debug(string $message, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### info

Normal but significant events

```php
public info(string $info, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$info` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### notice

Normal but significant events

```php
public notice(string $notice, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$notice` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### warning

Exceptional occurrences that are not errors

```php
public warning(string $warning, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$warning` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### alert

Action must be taken immediately

```php
public alert(string $alert, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$alert` | **string** |  |
| `$context` | **array&#124;null** |  |





***

### error

Report an error of application performance

```php
public error(string $errorType, string $message, string $file, int $line, mixed& $backtrace): bool
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
