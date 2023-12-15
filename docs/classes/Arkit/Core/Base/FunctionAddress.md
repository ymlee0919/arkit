***

# FunctionAddress

Class to store a class name and a method name.



* Full name: `\Arkit\Core\Base\FunctionAddress`




## Methods


### fromString

Build an instance of FunctionAddress given an string.

```php
public static fromString(string $strFunctionAddress): \Arkit\Core\Base\FunctionAddress
```

String format: ClassName[::FunctionName]

Note that FunctionName is optional.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$strFunctionAddress` | **string** | String with format: ClassName[::FunctionName] |





***

### __construct

Constructor of the class

```php
public __construct(string $className, string|null $functionName = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$className` | **string** | Name of the class |
| `$functionName` | **string&#124;null** | Function name |





***

### getClassName

Return the class name

```php
public getClassName(): string
```












***

### getFunctionName

Return the function name

```php
public getFunctionName(): string|null
```












***


***
> Automatically generated on 2023-12-15
