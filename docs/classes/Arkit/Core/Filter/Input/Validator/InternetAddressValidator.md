***

# InternetAddressValidator

Class InternetAddressValidator



* Full name: `\Arkit\Core\Filter\Input\Validator\InternetAddressValidator`
* Parent class: [`\Arkit\Core\Filter\Input\FieldValidator`](../FieldValidator.md)




## Methods


### check



```php
public check(): $this
```












***

### getValue



```php
public getValue(): string|null
```












***

### isIp



```php
public isIp(): $this
```












***

### isIpv4



```php
public isIpv4(): $this
```












***

### isIpv6



```php
public isIpv6(): $this
```












***

### isMacAddress



```php
public isMacAddress(): $this
```












***

### isValidUrl



```php
public isValidUrl(): $this
```












***

### isRemoteFile



```php
public isRemoteFile(): $this
```












***

### remoteFileSize



```php
public remoteFileSize(int $min_size, int $max_size): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min_size` | **int** |  |
| `$max_size` | **int** |  |





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
