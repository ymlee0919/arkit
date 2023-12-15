***

# CryptProviderInterface

Interface to define a cryptographic provider



* Full name: `\Arkit\Core\Security\Crypt\CryptProviderInterface`



## Methods


### getRandomString

Generate a pseudo-random string given the length

```php
public getRandomString(int $length): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$length` | **int** |  |





***

### oneWaySmoothCrypt

Make a smooth one way encryption

```php
public oneWaySmoothCrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | String to encrypt |


**Return Value:**

String encrypted




***

### oneWayStrongCrypt

Make a strong one way encryption

```php
public oneWayStrongCrypt(string $data, string|null $key = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **string&#124;null** | Internal key |





***

### twoWaysSmoothEncrypt

Make a smooth two ways encryption. You can get back the previous data using the twoWaySmoothDecrypt function.

```php
public twoWaysSmoothEncrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |


**Return Value:**

Internal key




**See Also:**

* \Arkit\Core\Security\Crypt\twoWaysSmoothDecrypt - 

***

### twoWaysSmoothDecrypt

Make a smooth two ways decryption. Get the data encrypted by twoWaySmoothEncrypt

```php
public twoWaysSmoothDecrypt(string $data): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |


**Return Value:**

Internal key




**See Also:**

* \Arkit\Core\Security\Crypt\twoWaysSmoothEncrypt - 

***

### twoWaysStrongEncrypt

Make a strong two ways encryption. You can get back the previous data using the twoWayStrongDecrypt function.

```php
public twoWaysStrongEncrypt(string $data, string $key): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **string** | Internal key |





**See Also:**

* \Arkit\Core\Security\Crypt\twoWaysStrongDecrypt - 

***

### twoWaysStrongDecrypt

Make a smooth two ways decryption. Get the data encrypted by twoWayStrongEncrypt, using the same key

```php
public twoWaysStrongDecrypt(string $data, string $key): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to decrypt |
| `$key` | **string** | Internal key |





**See Also:**

* \Arkit\Core\Security\Crypt\twoWaysStrongEncrypt - 

***


***
> Automatically generated on 2023-12-15
