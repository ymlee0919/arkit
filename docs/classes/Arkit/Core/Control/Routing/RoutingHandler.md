***

# RoutingHandler

Class to encapsulate the callback for routing result. Store the ruleId, handler and parameters



* Full name: `\Arkit\Core\Control\Routing\RoutingHandler`




## Methods


### __construct



```php
public __construct(string $ruleId, string $handler, array|null $parameters = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** | Id of the rule |
| `$handler` | **string** | Handler for the request |
| `$parameters` | **array&#124;null** | (Optional) Parameters taken from the url |





***

### getRuleId

Get the rule Id

```php
public getRuleId(): string
```












***

### getHandler

Get the function handler

```php
public getHandler(): string
```












***

### getParameters

Get parameters taken from the url

```php
public getParameters(): array|null
```












***

### haveParameters



```php
public haveParameters(): bool
```









**Return Value:**

Indicate if have parameters




***


***
> Automatically generated on 2023-12-15
