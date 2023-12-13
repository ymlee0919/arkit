***

# Router

Class Router



* Full name: `\Arkit\Core\Control\Routing\Router`
* This class is marked as **final** and can't be subclassed
* This class implements:
[`\Arkit\Core\Control\Routing\RouterInterface`](./RouterInterface.md)
* This class is a **Final class**




## Methods


### __construct



```php
public __construct(): mixed
```












***

### setSign

Set sign for caching

```php
public setSign(string $str): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$str` | **string** |  |





***

### getSign

Get sign for caching

```php
public getSign(): string
```












***

### setRules

Set array of routing rules.

```php
public setRules(array& $rules): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rules` | **array** |  |





***

### getRule

Get a routing rule given the ruleId

```php
public getRule(string $ruleId): \Arkit\Core\Control\Routing\RoutingRule|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** |  |





***

### setRule

Set a routing rule.

```php
public setRule(string $ruleId, array& $rule): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** | Rule Id |
| `$rule` | **array** | Array with routing information |





***

### route

Return and array with the given match rule.

```php
public route(string $url, string $method): ?\Arkit\Core\Control\Routing\RoutingHandler
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Requested url |
| `$method` | **string** | Requested method |





***

### buildUrl

Build the url given the ruleId and the array of parameters

```php
public buildUrl(string $ruleId, ?array $params = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** | ID of the rule |
| `$params` | **?array** | (Optional) Parameters of the URL |





***


***
> Automatically generated on 2023-12-13
