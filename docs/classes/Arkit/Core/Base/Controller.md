***

# Controller

Base class for handler request. Implements the Template Method pattern.

This is the order for call methods:
1.- init(): Initialize the handler
2.- validateIncomingRequest(): Check headers and http request information
3.- getAccessController(): Return an object for authorization
4.- prepare(): Prepare the last details before call the requested method
5.- Invoke the requested method

* Full name: `\Arkit\Core\Base\Controller`
* This class is an **Abstract class**




## Methods


### init

Initialize the handler

```php
public init(array|null $config = null): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array&#124;null** | Configuration array |





***

### validateIncomingRequest

Validate the incoming request.

```php
public validateIncomingRequest(): bool
```

Check headers and http request information


* This method is **abstract**.







***

### getAccessController

Return and object to validate if the client can access the requested resource

```php
public getAccessController(): \Arkit\Core\Control\Access\AccessControllerInterface
```




* This method is **abstract**.







***

### prepare

Prepare the handler before attend the request

```php
public prepare(): void
```




* This method is **abstract**.







***


***
> Automatically generated on 2023-12-13
