***

# AccessControlHelper

Helper for access control



* Full name: `\Arkit\Helper\Access\AccessControlHelper`




## Methods


### __construct

Constructor of the class

```php
public __construct(): mixed
```












***

### init

Init the controller

```php
public init(array $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Array with roles_source and tasks_source files |





***

### checkTaskAccess



```php
public checkTaskAccess(string $task, string $roles): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$task` | **string** |  |
| `$roles` | **string** |  |





***

### checkRoutingAccess



```php
public checkRoutingAccess(string $routingId, string $roles): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$routingId` | **string** |  |
| `$roles` | **string** |  |





***


***
> Automatically generated on 2023-12-15
