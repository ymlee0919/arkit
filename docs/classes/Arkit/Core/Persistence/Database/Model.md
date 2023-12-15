***

# Model

Abstraction for a core class of database connection



* Full name: `\Arkit\Core\Persistence\Database\Model`



## Methods


### connect

Connet to database with a given account

```php
public connect(string $account): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$account` | **string** | Account name used to connect |





***

### beginTransaction

Start a transaction

```php
public beginTransaction(): void
```












***

### commit

Commit a transaction

```php
public commit(): void
```












***

### rollback

Rollback a transaction

```php
public rollback(): void
```












***

### release

Release the connection

```php
public release(): void
```












***


***
> Automatically generated on 2023-12-15
