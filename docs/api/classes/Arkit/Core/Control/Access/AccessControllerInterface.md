***

# AccessControllerInterface





* Full name: `\Arkit\Core\Control\Access\AccessControllerInterface`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`ACCESS_FORBIDDEN`|public| |&#039;FORBIDDEN&#039;|
|`ACCESS_GRANTED`|public| |&#039;GRANTED&#039;|
|`ACCESS_DENIED`|public| |&#039;DENIED&#039;|

## Methods


### checkAccess

Evaluate the access tu the given routing callback

```php
public checkAccess(\Arkit\Core\Control\Routing\RoutingHandler $handler): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$handler` | **\Arkit\Core\Control\Routing\RoutingHandler** |  |


**Return Value:**

One of the defined constants




***


***
> Automatically generated on 2023-12-13
