***

# StringValidator

Class StringValidator



* Full name: `\Arkit\Core\Filter\Input\Validator\StringValidator`
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
public check(): $this
```












***

### getValue



```php
public getValue(): mixed
```












***

### withLengthBetween



```php
public withLengthBetween(int $min, ?int $max = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **int** |  |
| `$max` | **?int** |  |





***

### wordsCount



```php
public wordsCount(int $min, int|null $max = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **int** |  |
| `$max` | **int&#124;null** |  |





***

### contains



```php
public contains(string $part, bool $matchCase = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$part` | **string** |  |
| `$matchCase` | **bool** |  |





***

### startWith



```php
public startWith(string $begin, bool $matchCase = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$begin` | **string** |  |
| `$matchCase` | **bool** |  |





***

### endWith



```php
public endWith(string $final, bool $matchCase = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$final` | **string** |  |
| `$matchCase` | **bool** |  |





***

### matchWith



```php
public matchWith(string $pattern): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$pattern` | **string** |  |





***

### isOneOf



```php
public isOneOf(array $items, bool $matchCase = true): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$items` | **array** |  |
| `$matchCase` | **bool** |  |





***

### matchWithAny



```php
public matchWithAny(array $items): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$items` | **array** |  |





***

### isCryptId



```php
public isCryptId( $prefix): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$prefix` | **** |  |




**Throws:**

- [`Exception`](../../../../../Exception.md)



***

### isGoogleCaptcha



```php
public isGoogleCaptcha(string $secretKey): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$secretKey` | **string** |  |





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
