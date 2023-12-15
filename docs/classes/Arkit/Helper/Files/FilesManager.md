***

# FilesManager

Manage files as atomic operations. If something is wrong, just rollback.



* Full name: `\Arkit\Helper\Files\FilesManager`




## Methods


### fileType

Get the file type

```php
public static fileType(string $filePath): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filePath` | **string** | Full file path |





***

### generateRandomFileName

Generate a random file name given an seed

```php
public static generateRandomFileName(string $seed): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$seed` | **string** | Seed |





***

### __construct

Constructor of the class. Initialize all internal variables.

```php
public __construct(): mixed
```












***

### commit

Commit all pending changes

```php
public commit(): void
```












***

### rollback

Rollback all pending changes

```php
public rollback(): void
```












***

### uploadFile

Upload a file

```php
public uploadFile(string $fileIndex, string $destinationDirectory, string|null $fileName = null): bool|string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fileIndex` | **string** | File index into $_FILE array |
| `$destinationDirectory` | **string** | Destination directory |
| `$fileName` | **string&#124;null** | New file name. If not set, conserve the original |


**Return Value:**

Return the name of the file or false if any error.




***

### delete

Delete a file

```php
public delete(string $directory, string $fileName, bool $delay = true): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** | Directory path where the file is located |
| `$fileName` | **string** | File name |
| `$delay` | **bool** | Set false for delete at the moment, leave true for delete when commit. |





***

### rename

Rename a file

```php
public rename(string $directory, string $fileName, string $newName, bool $delay = true): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** | Directory path where the file is located |
| `$fileName` | **string** | File name |
| `$newName` | **string** | New file name |
| `$delay` | **bool** | Set false for rename at the moment, leave true for rename when commit. |





***


***
> Automatically generated on 2023-12-15
