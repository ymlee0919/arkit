***

# AccessTree

Internal class for access control using tree (Under construction)



* Full name: `\Arkit\Helper\Access\AccessTree`




## Methods


### __construct



```php
public __construct(): mixed
```












***

### build



```php
public build(array $roles, array $tasks): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$roles` | **array** |  |
| `$tasks` | **array** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### haveAccess



```php
public haveAccess(string $role, string $task): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$role` | **string** |  |
| `$task` | **string** |  |





***

### canInvoke



```php
public canInvoke(string $role, string $route): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$role` | **string** |  |
| `$route` | **string** |  |





***

### getSign



```php
public getSign(): string
```












***

### setSign



```php
public setSign(string $sign): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$sign` | **string** |  |





***


***
> Automatically generated on 2023-12-15
