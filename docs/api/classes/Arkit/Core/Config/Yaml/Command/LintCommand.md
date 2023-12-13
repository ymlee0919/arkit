***

# LintCommand

Validates YAML files syntax and outputs encountered errors.



* Full name: `\Arkit\Core\Config\Yaml\Command\LintCommand`
* Parent class: [`Command`](../../Console/Command/Command.md)




## Methods


### __construct



```php
public __construct(string $name = null, callable $directoryIteratorProvider = null, callable $isReadableProvider = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$directoryIteratorProvider` | **callable** |  |
| `$isReadableProvider` | **callable** |  |





***

### configure



```php
protected configure(): mixed
```












***

### execute



```php
protected execute(\Arkit\Core\Config\Console\Input\InputInterface $input, \Arkit\Core\Config\Console\Output\OutputInterface $output): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **\Arkit\Core\Config\Console\Input\InputInterface** |  |
| `$output` | **\Arkit\Core\Config\Console\Output\OutputInterface** |  |





***

### complete



```php
public complete(\Arkit\Core\Config\Console\Completion\CompletionInput $input, \Arkit\Core\Config\Console\Completion\CompletionSuggestions $suggestions): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **\Arkit\Core\Config\Console\Completion\CompletionInput** |  |
| `$suggestions` | **\Arkit\Core\Config\Console\Completion\CompletionSuggestions** |  |





***


***
> Automatically generated on 2023-12-13
