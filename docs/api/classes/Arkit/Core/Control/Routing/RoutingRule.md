***

# RoutingRule

Class to encapsulate a rule of routing



* Full name: `\Arkit\Core\Control\Routing\RoutingRule`




## Methods


### fromArray

Build a Routing rule given an Id and an array with the information

```php
public static fromArray(string $ruleId, array $info): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** |  |
| `$info` | **array** |  |





***

### __construct



```php
public __construct(string $ruleId, string $url, string $method, string $callback, ?string $task = null, array|null $constraints = null, array|null $allowedParameters = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ruleId` | **string** | Rule ID |
| `$url` | **string** | General url format |
| `$method` | **string** | Method for request: &#039;GET&#039;, &#039;POST&#039;, etc. |
| `$callback` | **string** | Callback to handle the request [Directory.directory..file/Class::function] |
| `$task` | **?string** | Task: For control access |
| `$constraints` | **array&#124;null** | (Optional) Constraints for url parameters |
| `$allowedParameters` | **array&#124;null** | (Optional) Optionals parameters for &#039;*&#039; ending url |





***

### getId

Get the rule ID

```php
public getId(): string
```












***

### getUrl

Get the url format

```php
public getUrl(): string
```












***

### getRequestMethod

Get the requested method

```php
public getRequestMethod(): string
```












***

### getCallback

Get the callback to handle the request

```php
public getCallback(): string
```












***

### getTask

Get the task

```php
public getTask(): string|null
```












***

### getConstraints

Get the constraints for url parameters.

```php
public getConstraints(): array|null
```

If exists, return and associative array when the key is the name of the parameter
and the value is the regular expression to validate the parameter










***

### getConstraint

Return the constraint for the given url parameter, it is a regular expression

```php
public getConstraint(string $paramName): string|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$paramName` | **string** | Name of the parameter |





***

### getAllowedParameters

Return the list of get allowed parameters when the url end with '*'

```php
public getAllowedParameters(): array|null
```












***


***
> Automatically generated on 2023-12-13
