***

# OpensslCryptProvider

Cryptographic provider using Openssl algorithms



* Full name: `\Arkit\Core\Security\Crypt\OpensslCryptProvider`
* This class implements:
[`\Arkit\Core\Security\Crypt\CryptProviderInterface`](./CryptProviderInterface.md)



## Properties


### hashAlgo



```php
protected string $hashAlgo
```






***

### smoothCryptString



```php
protected string $smoothCryptString
```






***

### smoothCryptArray



```php
protected string[] $smoothCryptArray
```






***

## Methods


### __construct



```php
public __construct(string $hashAlgo): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$hashAlgo` | **string** |  |





***

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
public oneWayStrongCrypt(string $data, ?string $key = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **string** | Data to encrypt |
| `$key` | **?string** | Internal key |





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





***


***
> Automatically generated on 2023-12-15
