***

# DateTimeValidator

Class DateTimeValidator



* Full name: `\Arkit\Core\Filter\Input\Validator\DateTimeValidator`
* Parent class: [`\Arkit\Core\Filter\Input\FieldValidator`](../FieldValidator.md)




## Methods


### setFormat



```php
public setFormat( $format): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **** | string |





***

### getValue



```php
public getValue(): \DateTime|null
```












***

### check



```php
public check(): $this
```












***

### isBefore



```php
public isBefore(string|\DateTime $value, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string&#124;\DateTime** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### isAfter



```php
public isAfter(string|\DateTime $value, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string&#124;\DateTime** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### between



```php
public between(string|\DateTime $min, string|\DateTime $max, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **string&#124;\DateTime** |  |
| `$max` | **string&#124;\DateTime** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### notBetween



```php
public notBetween(string|\DateTime $min, string|\DateTime $max, bool $notEqual = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **string&#124;\DateTime** |  |
| `$max` | **string&#124;\DateTime** |  |
| `$notEqual` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



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
> Automatically generated on 2023-12-15
