***

# RouterInterface





* Full name: `\Arkit\Core\Control\Routing\RouterInterface`



## Methods


### setRule

Set a routing rule.

```php
public setRule(string $ruleId, array& $rule): void
```

Rule must contain:

url: '/url'
callback: 'model.controller.file/Class::Function'
method: 'POST'
constraints (optional):
    [parameterName]: Regular expression for url get parameters
allow (optional): [List of get parameters allowed into the URL]






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** | Rule Id |
| `$rule` | **array** | Array with routing information |





***

### setRules

Set array of routing rules.

```php
public setRules(array& $rules): void
```

Rules format:

rulerId:
     url: '/url'
     callback: 'model.controller.file/Class::Function'
     method: 'POST'
     constraints (optional):
         <parameter>: Regular expression for url get parameters
     allow (optional): [List of get parameters allowed into the URL]






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

### route

Return and array with the given match rule.

```php
public route(string $url, string $method): ?\Arkit\Core\Control\Routing\RoutingHandler
```

The array returned mush have:
- id: The rule id
- callback: The callback function [Directory.directory..file/Class::function]
- parameters: Parameters set by url






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Requested url |
| `$method` | **string** | Requested method |





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
