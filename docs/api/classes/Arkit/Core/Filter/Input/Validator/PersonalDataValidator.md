***

# PersonalDataValidator

Class FieldValidator



* Full name: `\Arkit\Core\Filter\Input\Validator\PersonalDataValidator`
* Parent class: [`\Arkit\Core\Filter\Input\FieldValidator`](../FieldValidator.md)




## Methods


### set



```php
public set(mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **mixed** |  |





***

### check



```php
public check(): self
```












***

### getValue



```php
public getValue(): mixed
```












***

### isEmail



```php
public isEmail(): $this
```












***

### isEmailList



```php
public isEmailList(string $separator = &#039;;&#039;): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$separator` | **string** |  |





***

### isPhone



```php
public isPhone(): $this
```












***

### isValidName



```php
public isValidName(): $this
```












***

### isValidUser



```php
public isValidUser(): $this
```












***

### isPassword



```php
public isPassword(): $this
```












***

### isStrongPassword



```php
public isStrongPassword(): $this
```












***


## Inherited methods


### __construct



```php
public __construct(\Arkit\Core\Filter\InputValidator& $form): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$form` | **\Arkit\Core\Filter\InputValidator** |  |





***

### set



```php
public set(mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **mixed** |  |





***

### check



```php
public check(): self
```




* This method is **abstract**.







***

### getValue



```php
public getValue(): mixed
```




* This method is **abstract**.







***

### registerError



```php
public registerError(string $error, array|null $params = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$error` | **string** |  |
| `$params` | **array&#124;null** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### checkValidEmpty



```php
public checkValidEmpty(): bool
```












***

### isEmpty



```php
public isEmpty(): bool
```












***

### notEmpty



```php
public notEmpty(): $this
```












***

### isValid



```php
public isValid(): bool
```












***


***
> Automatically generated on 2023-12-13
