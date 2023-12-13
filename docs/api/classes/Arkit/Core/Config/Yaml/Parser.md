***

# Parser

Parser parses YAML strings to convert them to PHP arrays.



* Full name: `\Arkit\Core\Config\Yaml\Parser`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`TAG_PATTERN`|public| |&#039;(?P&lt;tag&gt;![\\w!.\\/:-]+)&#039;|
|`BLOCK_SCALAR_HEADER_PATTERN`|public| |&#039;(?P&lt;separator&gt;\\||&gt;)(?P&lt;modifiers&gt;\\+|\\-|\\d+|\\+\\d+|\\-\\d+|\\d+\\+|\\d+\\-)?(?P&lt;comments&gt; +#.*)?&#039;|
|`REFERENCE_PATTERN`|public| |&#039;#^&amp;(?P&lt;ref&gt;[^ ]++) *+(?P&lt;value&gt;.*)#u&#039;|


## Methods


### parseFile

Parses a YAML file into a PHP value.

```php
public parseFile(string $filename, int $flags): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filename` | **string** | The path to the YAML file to be parsed |
| `$flags` | **int** | A bit field of Yaml::PARSE_* constants to customize the YAML parser behavior |




**Throws:**
<p>If the file could not be read or the YAML is not valid</p>

- [`ParseException`](./Exception/ParseException.md)



***

### parse

Parses a YAML string to a PHP value.

```php
public parse(string $value, int $flags): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **string** | A YAML string |
| `$flags` | **int** | A bit field of Yaml::PARSE_* constants to customize the YAML parser behavior |




**Throws:**
<p>If the YAML is not valid</p>

- [`ParseException`](./Exception/ParseException.md)



***


***
> Automatically generated on 2023-12-13
