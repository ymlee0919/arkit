***

# CryptInterface

Interface to define a cryptographic provider class



* Full name: `\Arkit\Core\Security\Crypt\CryptInterface`
* This class is an **Abstract class**



## Properties


### defaultKey

Default key for strong crypt algorithms

```php
protected ?string $defaultKey
```






***

### cryptProvider

Class to provide crypt algorithms

```php
protected ?\Arkit\Core\Security\Crypt\CryptProviderInterface $cryptProvider
```






***

## Methods


### getRandomString

Generate a random string given the length

```php
public getRandomString(int $length): string
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$length` | **int** | String length |





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
| `$data` | **string** | Data to encrypt |


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
| `$data` | **string** | Data to encrypt |





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
| `$data` | **string** | Data to encrypt |





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
> Automatically generated on 2023-12-15
