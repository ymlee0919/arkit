***

# FilesManager

Class FilesManager



* Full name: `\Arkit\Helper\Files\FilesManager`




## Methods


### fileType



```php
public static fileType(string $filePath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filePath` | **string** |  |





***

### generateRandomFileName



```php
public static generateRandomFileName(string $seed): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$seed` | **string** |  |





***

### __construct



```php
public __construct(): mixed
```












***

### commit



```php
public commit(): void
```












***

### rollback



```php
public rollback(): void
```












***

### uploadFile



```php
public uploadFile(string $fileIndex, string $destinationDirectory, string|null $fileName = null): bool|string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fileIndex` | **string** |  |
| `$destinationDirectory` | **string** |  |
| `$fileName` | **string&#124;null** |  |





***

### delete



```php
public delete(string $directory, string $fileName, bool $delay = true): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** |  |
| `$fileName` | **string** |  |
| `$delay` | **bool** |  |





***

### rename



```php
public rename(string $directory, string $fileName, string $newName, bool $delay = true): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** |  |
| `$fileName` | **string** |  |
| `$newName` | **string** |  |
| `$delay` | **bool** |  |





***


***
> Automatically generated on 2023-12-13
