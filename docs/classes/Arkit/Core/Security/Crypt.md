***

# Crypt

Class for handle cryptography



* Full name: `\Arkit\Core\Security\Crypt`
* Parent class: [`\Arkit\Core\Security\Crypt\CryptInterface`](./Crypt/CryptInterface.md)



## Properties


### hashAlgo



```php
protected string|null $hashAlgo
```






***

## Methods


### __construct

Constructor of the class

```php
public __construct(): mixed
```












***

### init



```php
public init(array& $config = []): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### setCryptProvider



```php
public setCryptProvider(\Arkit\Core\Security\Crypt\CryptProviderInterface $cryptProvider): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cryptProvider` | **\Arkit\Core\Security\Crypt\CryptProviderInterface** |  |





***

### getRandomString

Generate a random string given the length

```php
public getRandomString(int $length): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$length` | **int** |  |





***

### smoothCrypt

Make a smooth one way encryption

```php
public smoothCrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | String to encrypt |


**Return Value:**

String encrypted




***

### strongCrypt

Make a strong one way encryption

```php
public strongCrypt(string $data, ?string $key = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **?string** | Key used to encrypt |





***

### smoothEncrypt

Make a smooth two ways encryption. You can get back the previous data using the smoothDecrypt function.

```php
public smoothEncrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** |  |





***

### smoothDecrypt

Make a smooth two ways decryption. Get the data encrypted by smoothEncrypt

```php
public smoothDecrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** |  |





***

### strongEncrypt

Make a strong two ways encryption. You can get back the previous data using the strongDecrypt function.

```php
public strongEncrypt(string $data, ?string $key = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **?string** | Base-64 string key |





***

### strongDecrypt

Make a smooth two ways decryption. Get the data encrypted by strongEncrypt, using the same key

```php
public strongDecrypt(string $data, ?string $key = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to decrypt |
| `$key` | **?string** | Base-64 string key |





***


## Inherited methods


### getRandomString

Generate a random string given the length

```php
public getRandomString(int $length): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$length` | **int** |  |





***

### smoothCrypt

Make a smooth one way encryption

```php
public smoothCrypt(string $data): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | String to encrypt |


**Return Value:**

String encrypted




***

### strongCrypt

Make a strong one way encryption

```php
public strongCrypt(string $data, string|null $key = null): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **string&#124;null** | Key used to encrypt |





***

### smoothEncrypt

Make a smooth two ways encryption. You can get back the previous data using the smoothDecrypt function.

```php
public smoothEncrypt(string $data): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** |  |





**See Also:**

* \Arkit\Core\Security\Crypt\smoothDecrypt - 

***

### smoothDecrypt

Make a smooth two ways decryption. Get the data encrypted by smoothEncrypt

```php
public smoothDecrypt(string $data): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** |  |





**See Also:**

* \Arkit\Core\Security\Crypt\smoothEncrypt - 

***

### strongEncrypt

Make a strong two ways encryption. You can get back the previous data using the strongDecrypt function.

```php
public strongEncrypt(string $data, string|null $key = null): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **string&#124;null** | Base-64 string key |





**See Also:**

* \Arkit\Core\Security\Crypt\strongDecrypt - 

***

### strongDecrypt

Make a smooth two ways decryption. Get the data encrypted by strongEncrypt, using the same key

```php
public strongDecrypt(string $data, string|null $key = null): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to decrypt |
| `$key` | **string&#124;null** | Base-64 string key |





**See Also:**

* \Arkit\Core\Security\Crypt\strongEncrypt - 

***


***
> Automatically generated on 2023-12-13
