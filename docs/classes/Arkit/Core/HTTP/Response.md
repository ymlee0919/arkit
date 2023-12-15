***

# Response

Handle the response to the client



* Full name: `\Arkit\Core\HTTP\Response`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### __construct

Constructor of the class. Initilize all internal fields

```php
public __construct(): mixed
```












***

### init

Initialize the object with the internal configuration.

```php
public init(array& $config): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **array** | Internal configuration |





***

### setDispatcher

Set a dispatcher to process the response to the client

```php
public setDispatcher(\Arkit\Core\HTTP\Response\DispatcherInterface $dispatcher): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dispatcher` | **\Arkit\Core\HTTP\Response\DispatcherInterface** | Dispatchet that process the response |





***

### setStatus

Set http response status

```php
public setStatus(int $status): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$status` | **int** | Header status |





***

### setHeader

Set/add a http response header

```php
public setHeader(string $header, string|null $value = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$header` | **string** | Header name |
| `$value` | **string&#124;null** | Header value |





***

### onBeforeDisplay

Set onBeforeDisplay event handler. This event will be thron just before return the response

```php
public onBeforeDisplay(\Arkit\Core\Base\FunctionAddress $onBeforeDisplay): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$onBeforeDisplay` | **\Arkit\Core\Base\FunctionAddress** | Function address to handle the event before dispatch the payload |





***

### onNotFound

Set onNotFound event handler.

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

Encode an entry to html entities

```php
public toHtmlEntities(string|array& $param, bool $utf8Encode = true): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **string&#124;array** | Entry to transform |
| `$utf8Encode` | **bool** | Encode to utf-8 |





***

### getCookies

Get response the cookies list

```php
public getCookies(): \Arkit\Core\Persistence\Client\CookieStore
```












***

### assignFromFile

Assign output values taken from a yaml file

```php
public assignFromFile(string $field, string $filePath, bool $encodeFirst = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string** | Output variable name. This variable will contains all values. |
| `$filePath` | **string** | Absolute file path to read the values |
| `$encodeFirst` | **bool** | Encode values to html entities |
| `$toUtf8` | **bool** | Encode to utf-8. It works only if $encodeFirst is true. |





***

### assign

Assign a value to the output

```php
public assign(string|array $field, mixed|null $value = null, bool $encodeFirst = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **string&#124;array** | Output variable name. This will be the name into the client side. |
| `$value` | **mixed&#124;null** | Value to set |
| `$encodeFirst` | **bool** | Encode values to html entities |
| `$toUtf8` | **bool** | Encode to utf-8. It works only if $encodeFirst is true. |





***

### inputError

Send an input error to the output.

```php
public inputError(string $fieldName, string $error, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldName` | **string** | Input field name that provoke the the error |
| `$error` | **string** | Error message |
| `$encode` | **bool** | Encode the error message to html entities |
| `$toUtf8` | **bool** | Encode to utf-8. It works only if $encodeFirst is true. |





***

### inputErrors

Send some input errors to the output.

```php
public inputErrors(array $errors, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errors` | **array** | Array of errors. Each key must be the name of the file that provoke the error. The associated value is taken as the error message. |
| `$encode` | **bool** | Encode error messages to html entities |
| `$toUtf8` | **bool** | Encode to utf-8. It works only if $encode is true. |





***

### error

Send an error to the output.

```php
public error(string $errorType, string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errorType` | **string** | Error type |
| `$message` | **string** | Error message |
| `$encode` | **bool** | Encode error message to html entities |
| `$toUtf8` | **bool** | Encode the message to utf-8. It works only if $encode is true. |





***

### warning

Send a warning to the output

```php
public warning(string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | Warning message |
| `$encode` | **bool** | Encode warning message to html entities |
| `$toUtf8` | **bool** | Encode the message to utf-8. It works only if $encode is true. |





***

### success

Send a success message to the output

```php
public success(string $message, bool $encode = true, bool $toUtf8 = false): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | Message |
| `$encode` | **bool** | Encode the message to html entities |
| `$toUtf8` | **bool** | Encode the message to utf-8. It works only if $encode is true. |





***

### dispatch

Send the response to the client

```php
public dispatch(string|null $resource = null, array|null $arguments = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$resource` | **string&#124;null** | Resource used by the dispacther |
| `$arguments` | **array&#124;null** | Arguments used by the dispatcher |





***

### displayTemplate

Display a template. Set a TemplateDispatcher as internal dispatcher.

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



**See Also:**

* \Arkit\Core\HTTP\Response\TemplateDispatcher - 

***

### redirectTo

Redirect to the url build by router. Set a RedirectDispatcher as internal dispatcher.

```php
public redirectTo(string $urlId, array|null $params = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$urlId` | **string** | Rule id of the current url router |
| `$params` | **array&#124;null** | (optional) Parameters to build the url |





**See Also:**

* \Arkit\Core\HTTP\Response\RedirectDispatcher - 

***

### redirectToUrl

Redirect to a given URL. Set a RedirectDispatcher as internal dispatcher.

```php
public redirectToUrl(string $url): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$url` | **string** | Url to redirect |





**See Also:**

* \Arkit\Core\HTTP\Response\RedirectDispatcher - 

***

### toJSON

Dispatch values in JSON format. Set a JsonDispatcher as internal dispatcher.

```php
public toJSON(): void
```












**See Also:**

* \Arkit\Core\HTTP\Response\JsonDispatcher - 

***


***
> Automatically generated on 2023-12-15
