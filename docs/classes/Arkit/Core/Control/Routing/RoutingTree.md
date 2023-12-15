***

# RoutingTree

Class to handle routes, implemented as a tree.



* Full name: `\Arkit\Core\Control\Routing\RoutingTree`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### __construct

Constructor of the class RoutingTree

```php
public __construct(): mixed
```












***

### root

Get a root node given a request method

```php
public root(string $method): array|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | Request method |





***

### addRoutingRule

Add a routing rule

```php
public addRoutingRule(string $method, string $id, string $urlRequest, null|array $constraints = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | Http method |
| `$id` | **string** | Rule id |
| `$urlRequest` | **string** | Url |
| `$constraints` | **null&#124;array** | (Optional) Constraints of url parameters |





***

### getRoutingRules

Get list of rules given a request

```php
public getRoutingRules(string $method, string $urlRequest): array|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | Http request method |
| `$urlRequest` | **string** | Url requested |


**Return Value:**

List of routing rules




***


***
> Automatically generated on 2023-12-15
