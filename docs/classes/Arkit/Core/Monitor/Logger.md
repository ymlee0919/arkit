***

# Logger

Logs manager



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
| `$config` | **array** | Configuration array |





***

### init



```php
public init(): void
```












***

### setHandler

Set logs handler to events types

```php
public setHandler(\Arkit\Core\Monitor\Log\LogsHandlerInterface $handler, array $logTypes): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$handler` | **\Arkit\Core\Monitor\Log\LogsHandlerInterface** | LogsHandler |
| `$logTypes` | **array** | List of type of events |





***

### logRequest

Log a request

```php
public logRequest(\Arkit\Core\HTTP\RequestInterface& $request): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** | Http client request |





***

### log

Log an event

```php
public log(string $eventType, string $message, array|null $context = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$eventType` | **string** | Event type |
| `$message` | **string** | Message |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### debug

Detailed debug information.

```php
public debug(string $message, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | Message |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### info

Normal but significant events

```php
public info(string $info, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$info` | **string** | Message |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### notice

Normal but significant events

```php
public notice(string $notice, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$notice` | **string** | Notice text |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### warning

Exceptional occurrences that are not errors

```php
public warning(string $warning, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$warning` | **string** | Warning text |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### alert

Action must be taken immediately

```php
public alert(string $alert, array|null $context = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$alert` | **string** | Alert message |
| `$context` | **array&#124;null** | Log context, usualy taken from debug_backtrace |





***

### error

Report an error of application performance

```php
public error(string $errorType, string $message, string $file, int $line, mixed& $backtrace): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** | Error type |
| `$message` | **string** | Message |
| `$file` | **string** | File where the error happend |
| `$line` | **int** | Line number of the file |
| `$backtrace` | **mixed** | Callstack |





***


***
> Automatically generated on 2023-12-15
