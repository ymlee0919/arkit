***

# InputValidator

Class FormValidator



* Full name: `\Arkit\Core\Filter\InputValidator`




## Methods


### __construct

Constructor of the class

```php
public __construct(): mixed
```












***

### init



```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### generateCsrfCode



```php
public generateCsrfCode(?string $formId = null, ?string $fieldName = null, ?int $expire = null, bool $setCookie = false): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **?string** | - If formId is set, return the code. Otherwise, set into \Arkit\App::$store |
| `$fieldName` | **?string** | - Field name to generate the hidden html field. If not set, get the default csrf field name |
| `$expire` | **?int** | - Time in seconds of form expiration |
| `$setCookie` | **bool** | - Indicate if set cookies into the output as other token validation |





***

### validateCsrfCode



```php
public validateCsrfCode(string|null $formId = null, string|null $fieldName = null, bool $validateCookie = false): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string&#124;null** |  |
| `$fieldName` | **string&#124;null** |  |
| `$validateCookie` | **bool** |  |





***

### releaseCsrfCookie

Release the cookie sent with the form

```php
public releaseCsrfCookie(string|null $formId = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string&#124;null** |  |





***

### setId



```php
public setId(string $formId): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$formId` | **string** |  |





***

### checkValues



```php
public checkValues(\Arkit\Core\HTTP\RequestInterface& $request): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** |  |





***

### registerError



```php
public registerError(string $error, ?array $params = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$error` | **string** |  |
| `$params` | **?array** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)



***

### registerCustomError



```php
public registerCustomError(mixed $errorMessage): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorMessage` | **mixed** |  |





***

### getError



```php
public getError(string $field): ?string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |





***

### getErrors



```php
public getErrors(): array
```












***

### isValid



```php
public isValid(): bool
```












***

### validate



```php
public validate(string $field, mixed $value = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$value` | **mixed** |  |





***

### validateFile



```php
public validateFile(string $fileIndex): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fileIndex` | **string** |  |





***

### alias



```php
public alias(string $alias): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$alias` | **string** |  |





***

### isRequired



```php
public isRequired(?string $errorMessage = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorMessage` | **?string** |  |





***

### notEmpty



```php
public notEmpty(?string $errorMessage = null): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorMessage` | **?string** |  |





***

### setCustomError



```php
public setCustomError(string $error): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$error` | **string** |  |





***

### setDateFormat



```php
public setDateFormat(string $format): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string** |  |





***

### setDateTimeFormat



```php
public setDateTimeFormat(string $format): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string** |  |





***

### storeErrorsInSession



```php
public storeErrorsInSession(string $sessionKey = &#039;FROM_ERRORS&#039;, bool $asFlash = true): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$sessionKey` | **string** |  |
| `$asFlash` | **bool** |  |





***

### setLanguage



```php
public setLanguage(string $language): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$language` | **string** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### isInteger



```php
public isInteger(): \Arkit\Core\Filter\Input\Validator\IntValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isNumeric



```php
public isNumeric(): \Arkit\Core\Filter\Input\Validator\NumericValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isBoolean



```php
public isBoolean(): \Arkit\Core\Filter\Input\Validator\BoolValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isInternetAddress



```php
public isInternetAddress(): \Arkit\Core\Filter\Input\Validator\InternetAddressValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isPersonalData



```php
public isPersonalData(): \Arkit\Core\Filter\Input\Validator\PersonalDataValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isCreditCard



```php
public isCreditCard(): \Arkit\Core\Filter\Input\Validator\CreditCardValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isString



```php
public isString(): \Arkit\Core\Filter\Input\Validator\StringValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isStrNumber



```php
public isStrNumber(): \Arkit\Core\Filter\Input\Validator\StrNumberValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isDateTime



```php
public isDateTime(string|null $format = null): \Arkit\Core\Filter\Input\Validator\DateTimeValidator
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### isDate



```php
public isDate(string|null $format = null): \Arkit\Core\Filter\Input\Validator\DateValidator
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string&#124;null** |  |




**Throws:**

- [`Exception`](../../../Exception.md)



***

### isFile



```php
public isFile(): \Arkit\Core\Filter\Input\Validator\FileValidator
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### isCustom



```php
public isCustom(\Arkit\Core\Filter\Input\FieldValidator $fieldValidator): \Arkit\Core\Filter\Input\FieldValidator
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldValidator` | **\Arkit\Core\Filter\Input\FieldValidator** |  |





***

### purify



```php
public purify(?string $value = null): string|$this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **?string** | Value to purify, null for the current value |




**Throws:**

- [`Exception`](../../../Exception.md)



***


***
> Automatically generated on 2023-12-15
