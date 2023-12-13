***

# Response

Class Output



* Full name: `\Arkit\Core\HTTP\Response`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




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





***

### setDispatcher



```php
public setDispatcher(\Arkit\Core\HTTP\Response\DispatcherInterface $dispatcher): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dispatcher` | **\Arkit\Core\HTTP\Response\DispatcherInterface** |  |





***

### setStatus

Set response status

```php
public setStatus(int $status): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$status` | **int** |  |





***

### setHeader



```php
public setHeader(string $header, string|null $value = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$header` | **string** |  |
| `$value` | **string&#124;null** |  |





***

### onBeforeDisplay

Set onBeforeDisplay event handler

```php
public onBeforeDisplay(\Arkit\Core\Base\FunctionAddress $onBeforeDisplay): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onBeforeDisplay` | **\Arkit\Core\Base\FunctionAddress** | Function address to handle the event before dispatch the payload |





***

### onNotFound

Set onNotFound event handler

```php
public onNotFound(\Arkit\Core\Base\FunctionAddress $onNotFound): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onNotFound` | **\Arkit\Core\Base\FunctionAddress** | Function address to handle the event when request is not found |





***

### throwNotFound

Respond not found error - 404 Response

```php
public throwNotFound(): void
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### onAccessDenied

Set onAccessDenied event handler

```php
public onAccessDenied(\Arkit\Core\Base\FunctionAddress $onAccessDenied): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onAccessDenied` | **\Arkit\Core\Base\FunctionAddress** | Function address to handle the event when access is denied |





***

### throwAccessDenied

Respond unauthorized error - 401 Response

```php
public throwAccessDenied(): void
```












***

### onForbiddenAccess

Set onForbiddenAccess event handler

```php
public onForbiddenAccess(\Arkit\Core\Base\FunctionAddress $onForbiddenAccess): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onForbiddenAccess` | **\Arkit\Core\Base\FunctionAddress** | Function address to handle the event when access is forbidden |





***

### throwForbiddenAccess

Respond forbidden error - 403 Response

```php
public throwForbiddenAccess(): void
```












***

### throwInvalidRequest

Throw internal 400 error because invalid domain

```php
public throwInvalidRequest(): void
```












***

### toHtmlEntities

Encode to html entities

```php
public toHtmlEntities(string|array& $param, bool $utf8Encode = true): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **string&#124;array** |  |
| `$utf8Encode` | **bool** |  |





***

### getCookies



```php
public getCookies(): \Arkit\Core\Persistence\Client\CookieStore
```












***

### assignFromFile

Assign a values to the template from a file

```php
public assignFromFile(string $field, string $filePath, bool $encodeFirst = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** |  |
| `$filePath` | **string** |  |
| `$encodeFirst` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### assign

Assign a value to the template

```php
public assign(string|array $field, mixed|null $value = null, bool $encodeFirst = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string&#124;array** |  |
| `$value` | **mixed&#124;null** |  |
| `$encodeFirst` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### inputError



```php
public inputError(string $fieldName, string $error, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** |  |
| `$error` | **string** |  |
| `$encode` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### inputErrors



```php
public inputErrors(array $errors, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errors` | **array** |  |
| `$encode` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### error



```php
public error(string $errorType, string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** |  |
| `$message` | **string** |  |
| `$encode` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### warning



```php
public warning(string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |
| `$encode` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### success



```php
public success(string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |
| `$encode` | **bool** |  |
| `$toUtf8` | **bool** |  |





***

### dispatch



```php
public dispatch(?string $resource, array|null $arguments = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$resource` | **?string** |  |
| `$arguments` | **array&#124;null** |  |





***

### displayTemplate

Display a template

```php
public displayTemplate(string $template, string|null $cacheId = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$template` | **string** | Template name or full template name<br />&lt;ul&gt;<br /> &lt;li&gt;If set the template name, the folder is &#039;view&#039; at same level of the current controller&lt;/li&gt;<br /> &lt;li&gt;Provide full path for custom folder&lt;/li&gt;<br />&lt;/ul&gt; |
| `$cacheId` | **string&#124;null** |  |




**Throws:**

- [`SmartyException`](../../../SmartyException.md)

- [`Exception`](../../../Exception.md)



***

### redirectTo

Redirect to the url build by router

```php
public redirectTo(string $urlId, array|null $params = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$urlId` | **string** |  |
| `$params` | **array&#124;null** |  |





***

### redirectToUrl

Redirect to a given URL

```php
public redirectToUrl(string $url): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** |  |





***

### toJSON

Dispatch values in JSON format

```php
public toJSON(): void
```












***


***
> Automatically generated on 2023-12-13
