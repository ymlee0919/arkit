***

# IntValidator

Class IntValidator



* Full name: `\Arkit\Core\Filter\Input\Validator\IntValidator`
* Parent class: [`\Arkit\Core\Filter\Input\FieldValidator`](../FieldValidator.md)




## Methods


### check



```php
public check(): $this
```












***

### getValue



```php
public getValue(): int|null
```












***

### greaterThan



```php
public greaterThan(int $value, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **int** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### lessThan



```php
public lessThan(int $value, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **int** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### between



```php
public between(int $min, int $max, bool $equal = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **int** |  |
| `$max` | **int** |  |
| `$equal` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### notBetween



```php
public notBetween(int $min, int $max, bool $notEqual = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **int** |  |
| `$max` | **int** |  |
| `$notEqual` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../../../InvalidArgumentException.md)



***

### isPositive



```php
public isPositive(bool $zeroIncluded = false): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$zeroIncluded` | **bool** | Indicate if the 0 value can be included |





***

### isNegative



```php
public isNegative(bool $zeroIncluded = false): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$zeroIncluded` | **bool** | Indicate if the 0 value can be included |





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
