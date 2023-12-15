
***

# Arkit API



This is an automatically generated documentation for **Arkit API**.


## Namespaces


### \

#### Classes

| Class | Description |
|-------|-------------|
| [`Loader`](./classes/Loader.md) | Class to handle loading files and dependencies.|




### \Arkit

#### Classes

| Class | Description |
|-------|-------------|
| [`App`](./classes/Arkit/App.md) | Application controller class. Implements the singleton pattern.|




### \Arkit\Core\Base

#### Classes

| Class | Description |
|-------|-------------|
| [`Controller`](./classes/Arkit/Core/Base/Controller.md) | Base class for handler request. Implements the Template Method pattern.|
| [`FunctionAddress`](./classes/Arkit/Core/Base/FunctionAddress.md) | Class to store a class name and a method name.|




### \Arkit\Core\Config

#### Classes

| Class | Description |
|-------|-------------|
| [`DotEnv`](./classes/Arkit/Core/Config/DotEnv.md) | Environment-specific configuration handler.|
| [`YamlReader`](./classes/Arkit/Core/Config/YamlReader.md) | Yaml file reader|




### \Arkit\Core\Control\Access




#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`AccessControllerInterface`](./classes/Arkit/Core/Control/Access/AccessControllerInterface.md) | Interface for Access Controller clases.|



### \Arkit\Core\Control\Routing

#### Classes

| Class | Description |
|-------|-------------|
| [`DomainRouter`](./classes/Arkit/Core/Control/Routing/DomainRouter.md) | Router class for domain|
| [`Router`](./classes/Arkit/Core/Control/Routing/Router.md) | Class Router|
| [`RoutingHandler`](./classes/Arkit/Core/Control/Routing/RoutingHandler.md) | Class to encapsulate the callback for routing result. Store the ruleId, handler and parameters|
| [`RoutingRule`](./classes/Arkit/Core/Control/Routing/RoutingRule.md) | Class to encapsulate a rule of routing|
| [`RoutingTree`](./classes/Arkit/Core/Control/Routing/RoutingTree.md) | Class to handle routes, implemented as a tree.|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`RouterInterface`](./classes/Arkit/Core/Control/Routing/RouterInterface.md) | Interface for an url ruoter|



### \Arkit\Core\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`DatabasePerformanceException`](./classes/Arkit/Core/Exception/DatabasePerformanceException.md) | General class for any database performace exception|
| [`InvalidActionException`](./classes/Arkit/Core/Exception/InvalidActionException.md) | General class for any invalid action exception|




### \Arkit\Core\Filter

#### Classes

| Class | Description |
|-------|-------------|
| [`InputValidator`](./classes/Arkit/Core/Filter/InputValidator.md) | Class FormValidator|




### \Arkit\Core\Filter\Input

#### Classes

| Class | Description |
|-------|-------------|
| [`CSRFHandler`](./classes/Arkit/Core/Filter/Input/CSRFHandler.md) | This class handle the CSRF token.|
| [`FieldValidator`](./classes/Arkit/Core/Filter/Input/FieldValidator.md) | Class FieldValidator|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`InputProtectorInterface`](./classes/Arkit/Core/Filter/Input/InputProtectorInterface.md) | |



### \Arkit\Core\Filter\Input\Validator

#### Classes

| Class | Description |
|-------|-------------|
| [`BoolValidator`](./classes/Arkit/Core/Filter/Input/Validator/BoolValidator.md) | Class BoolValidator|
| [`CreditCardValidator`](./classes/Arkit/Core/Filter/Input/Validator/CreditCardValidator.md) | Class FieldValidator|
| [`DateTimeValidator`](./classes/Arkit/Core/Filter/Input/Validator/DateTimeValidator.md) | Class DateTimeValidator|
| [`DateValidator`](./classes/Arkit/Core/Filter/Input/Validator/DateValidator.md) | Class DateValidator|
| [`FileValidator`](./classes/Arkit/Core/Filter/Input/Validator/FileValidator.md) | Class BoolValidator|
| [`IntValidator`](./classes/Arkit/Core/Filter/Input/Validator/IntValidator.md) | Class IntValidator|
| [`InternetAddressValidator`](./classes/Arkit/Core/Filter/Input/Validator/InternetAddressValidator.md) | Class InternetAddressValidator|
| [`NumericValidator`](./classes/Arkit/Core/Filter/Input/Validator/NumericValidator.md) | Class NumericValidator|
| [`PersonalDataValidator`](./classes/Arkit/Core/Filter/Input/Validator/PersonalDataValidator.md) | Class FieldValidator|
| [`StrNumberValidator`](./classes/Arkit/Core/Filter/Input/Validator/StrNumberValidator.md) | Class StrNumberValidator|
| [`StringValidator`](./classes/Arkit/Core/Filter/Input/Validator/StringValidator.md) | Class StringValidator|




### \Arkit\Core\HTTP

#### Classes

| Class | Description |
|-------|-------------|
| [`Request`](./classes/Arkit/Core/HTTP/Request.md) | Client request handler|
| [`Response`](./classes/Arkit/Core/HTTP/Response.md) | Handle the response to the client|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`RequestInterface`](./classes/Arkit/Core/HTTP/RequestInterface.md) | Interface to define a client request handler class|



### \Arkit\Core\HTTP\Request

#### Classes

| Class | Description |
|-------|-------------|
| [`JsonParser`](./classes/Arkit/Core/HTTP/Request/JsonParser.md) | Parser of request payload in json format|
| [`MultipartFormParser`](./classes/Arkit/Core/HTTP/Request/MultipartFormParser.md) | Parser of request payload in MultipartForm format|
| [`PayloadParserInterface`](./classes/Arkit/Core/HTTP/Request/PayloadParserInterface.md) | Interface to define a class for parse the http request payload|
| [`UrlEncodedParser`](./classes/Arkit/Core/HTTP/Request/UrlEncodedParser.md) | Parser of request payload in url enconded format. It is the default format.|




### \Arkit\Core\HTTP\Response

#### Classes

| Class | Description |
|-------|-------------|
| [`JsonDispatcher`](./classes/Arkit/Core/HTTP/Response/JsonDispatcher.md) | Response dispatcher in Json format|
| [`RedirectDispatcher`](./classes/Arkit/Core/HTTP/Response/RedirectDispatcher.md) | Response dispatcher thru redirection. All previous values sent to the output are stored into a ViewFlashMemory.|
| [`TemplateDispatcher`](./classes/Arkit/Core/HTTP/Response/TemplateDispatcher.md) | Dispatch the response using a template engine|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`DispatcherInterface`](./classes/Arkit/Core/HTTP/Response/DispatcherInterface.md) | Interface to define a payload response dispatcher|



### \Arkit\Core\HTTP\Response\Template

#### Classes

| Class | Description |
|-------|-------------|
| [`Template`](./classes/Arkit/Core/HTTP/Response/Template/Template.md) | Class to define a template. Smarty is used as template engine.|
| [`TinyTemplate`](./classes/Arkit/Core/HTTP/Response/Template/TinyTemplate.md) | Tiny template engine. It only allow direct variables replacement.|




### \Arkit\Core\Monitor

#### Classes

| Class | Description |
|-------|-------------|
| [`ErrorHandler`](./classes/Arkit/Core/Monitor/ErrorHandler.md) | Class to handle internar errors of the application|
| [`Logger`](./classes/Arkit/Core/Monitor/Logger.md) | Logs manager|




### \Arkit\Core\Monitor\Log

#### Classes

| Class | Description |
|-------|-------------|
| [`EmailLogsHandler`](./classes/Arkit/Core/Monitor/Log/EmailLogsHandler.md) | Logs handler using email|
| [`FileLogsHandler`](./classes/Arkit/Core/Monitor/Log/FileLogsHandler.md) | Logs handler using files|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`LogsHandlerInterface`](./classes/Arkit/Core/Monitor/Log/LogsHandlerInterface.md) | Interface that must implement each log handler|



### \Arkit\Core\Persistence\Cache

#### Classes

| Class | Description |
|-------|-------------|
| [`ApcCacheEngine`](./classes/Arkit/Core/Persistence/Cache/ApcCacheEngine.md) | Cache engine over Apc|
| [`FileCacheEngine`](./classes/Arkit/Core/Persistence/Cache/FileCacheEngine.md) | Cache engine over file|
| [`MemcacheCacheEngine`](./classes/Arkit/Core/Persistence/Cache/MemcacheCacheEngine.md) | Cache engine over Memcache|
| [`MemcachedCacheEngine`](./classes/Arkit/Core/Persistence/Cache/MemcachedCacheEngine.md) | Cache Engine over Memcached|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CacheInterface`](./classes/Arkit/Core/Persistence/Cache/CacheInterface.md) | Interface to define a Cache engine|



### \Arkit\Core\Persistence\Client

#### Classes

| Class | Description |
|-------|-------------|
| [`Cookie`](./classes/Arkit/Core/Persistence/Client/Cookie.md) | Cookie handler class|
| [`CookieStore`](./classes/Arkit/Core/Persistence/Client/CookieStore.md) | Store of cookies|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CookieInterface`](./classes/Arkit/Core/Persistence/Client/CookieInterface.md) | Interface for a value object representation of an HTTP cookie.|



### \Arkit\Core\Persistence\Database




#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`Model`](./classes/Arkit/Core/Persistence/Database/Model.md) | Abstraction for a core class of database connection|



### \Arkit\Core\Persistence\Server

#### Classes

| Class | Description |
|-------|-------------|
| [`Session`](./classes/Arkit/Core/Persistence/Server/Session.md) | Session variables manager. Implements the singleton pattern.|




### \Arkit\Core\Security

#### Classes

| Class | Description |
|-------|-------------|
| [`Crypt`](./classes/Arkit/Core/Security/Crypt.md) | Class for handle cryptography|




### \Arkit\Core\Security\Crypt

#### Classes

| Class | Description |
|-------|-------------|
| [`CryptInterface`](./classes/Arkit/Core/Security/Crypt/CryptInterface.md) | Interface to define a cryptographic provider class|
| [`OpensslCryptProvider`](./classes/Arkit/Core/Security/Crypt/OpensslCryptProvider.md) | Cryptographic provider using Openssl algorithms|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CryptProviderInterface`](./classes/Arkit/Core/Security/Crypt/CryptProviderInterface.md) | Interface to define a cryptographic provider|



### \Arkit\Helper\Access

#### Classes

| Class | Description |
|-------|-------------|
| [`AccessControlHelper`](./classes/Arkit/Helper/Access/AccessControlHelper.md) | Helper for access control|
| [`AccessHash`](./classes/Arkit/Helper/Access/AccessHash.md) | Internal class for access control using hash|
| [`AccessTree`](./classes/Arkit/Helper/Access/AccessTree.md) | Internal class for access control using tree (Under construction)|




### \Arkit\Helper\Files

#### Classes

| Class | Description |
|-------|-------------|
| [`FilesManager`](./classes/Arkit/Helper/Files/FilesManager.md) | Manage files as atomic operations. If something is wrong, just rollback.|




### \Arkit\Helper\View

#### Classes

| Class | Description |
|-------|-------------|
| [`ViewFlashMemory`](./classes/Arkit/Helper/View/ViewFlashMemory.md) | Class to store flash values in session|




### \Arkit\Services\Email

#### Classes

| Class | Description |
|-------|-------------|
| [`EmailDispatcher`](./classes/Arkit/Services/Email/EmailDispatcher.md) | Email dispatcher (Under construction)|




### \Arkit\Services\Payment

#### Classes

| Class | Description |
|-------|-------------|
| [`Checkout`](./classes/Arkit/Services/Payment/Checkout.md) | Check out service (Under construction)|




### \Arkit\Services\Pdf

#### Classes

| Class | Description |
|-------|-------------|
| [`PdfBuilder`](./classes/Arkit/Services/Pdf/PdfBuilder.md) | Class for building a pdf from a web page (Under construction)|




***
> Automatically generated on 2023-12-15
