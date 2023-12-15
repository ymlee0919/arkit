***

# InputProtectorInterface





* Full name: `\Arkit\Core\Filter\Input\InputProtectorInterface`



## Methods


### init



```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |





***

### generateProtectionCode



```php
public generateProtectionCode(string $formId, ?int $expire = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$expire` | **?int** |  |





***

### generateCookie



```php
public generateCookie(string $formId, ?int $expire = null, string $path = &#039;/&#039;): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$expire` | **?int** |  |
| `$path` | **string** |  |





***

### validateProtectionCode



```php
public validateProtectionCode(string $formId, string $code): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |
| `$code` | **string** |  |





***

### validateCookie



```php
public validateCookie(string $formId): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |





***


***
> Automatically generated on 2023-12-15
