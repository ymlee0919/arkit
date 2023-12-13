
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
| [`FunctionAddress`](./classes/Arkit/Core/Base/FunctionAddress.md) | |




### \Arkit\Core\Config

#### Classes

| Class | Description |
|-------|-------------|
| [`DotEnv`](./classes/Arkit/Core/Config/DotEnv.md) | Environment-specific configuration|
| [`YamlReader`](./classes/Arkit/Core/Config/YamlReader.md) | |




### \Arkit\Core\Control\Access




#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`AccessControllerInterface`](./classes/Arkit/Core/Control/Access/AccessControllerInterface.md) | |



### \Arkit\Core\Control\Routing

#### Classes

| Class | Description |
|-------|-------------|
| [`DomainRouter`](./classes/Arkit/Core/Control/Routing/DomainRouter.md) | Class for route a domain|
| [`Router`](./classes/Arkit/Core/Control/Routing/Router.md) | Class Router|
| [`RoutingHandler`](./classes/Arkit/Core/Control/Routing/RoutingHandler.md) | Class to encapsulate the callback for routing result|
| [`RoutingRule`](./classes/Arkit/Core/Control/Routing/RoutingRule.md) | Class to encapsulate a rule of routing|
| [`RoutingTree`](./classes/Arkit/Core/Control/Routing/RoutingTree.md) | Class RoutingTree, used by the current Router|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`RouterInterface`](./classes/Arkit/Core/Control/Routing/RouterInterface.md) | |



### \Arkit\Core\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`DatabasePerformanceException`](./classes/Arkit/Core/Exception/DatabasePerformanceException.md) | |
| [`InvalidActionException`](./classes/Arkit/Core/Exception/InvalidActionException.md) | |




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
| [`Request`](./classes/Arkit/Core/HTTP/Request.md) | Class Request|
| [`Response`](./classes/Arkit/Core/HTTP/Response.md) | Class Output|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`RequestInterface`](./classes/Arkit/Core/HTTP/RequestInterface.md) | Class RequestInterface|



### \Arkit\Core\HTTP\Request

#### Classes

| Class | Description |
|-------|-------------|
| [`BodyParserInterface`](./classes/Arkit/Core/HTTP/Request/BodyParserInterface.md) | |
| [`JsonParser`](./classes/Arkit/Core/HTTP/Request/JsonParser.md) | |
| [`MultipartFormParser`](./classes/Arkit/Core/HTTP/Request/MultipartFormParser.md) | |
| [`UrlEncodedParser`](./classes/Arkit/Core/HTTP/Request/UrlEncodedParser.md) | |




### \Arkit\Core\HTTP\Response

#### Classes

| Class | Description |
|-------|-------------|
| [`JsonDispatcher`](./classes/Arkit/Core/HTTP/Response/JsonDispatcher.md) | |
| [`RedirectDispatcher`](./classes/Arkit/Core/HTTP/Response/RedirectDispatcher.md) | |
| [`TemplateDispatcher`](./classes/Arkit/Core/HTTP/Response/TemplateDispatcher.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`DispatcherInterface`](./classes/Arkit/Core/HTTP/Response/DispatcherInterface.md) | Class for dispatch the payload response|



### \Arkit\Core\HTTP\Response\Template

#### Classes

| Class | Description |
|-------|-------------|
| [`Template`](./classes/Arkit/Core/HTTP/Response/Template/Template.md) | |
| [`TinyTemplate`](./classes/Arkit/Core/HTTP/Response/Template/TinyTemplate.md) | Class TinyTemplate|




### \Arkit\Core\Monitor

#### Classes

| Class | Description |
|-------|-------------|
| [`ErrorHandler`](./classes/Arkit/Core/Monitor/ErrorHandler.md) | Class Router|
| [`Logger`](./classes/Arkit/Core/Monitor/Logger.md) | Class LogsManager|




### \Arkit\Core\Monitor\Log

#### Classes

| Class | Description |
|-------|-------------|
| [`EmailLogsHandler`](./classes/Arkit/Core/Monitor/Log/EmailLogsHandler.md) | |
| [`FileLogsHandler`](./classes/Arkit/Core/Monitor/Log/FileLogsHandler.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`LogsHandlerInterface`](./classes/Arkit/Core/Monitor/Log/LogsHandlerInterface.md) | |



### \Arkit\Core\Persistence\Cache

#### Classes

| Class | Description |
|-------|-------------|
| [`ApcCacheEngine`](./classes/Arkit/Core/Persistence/Cache/ApcCacheEngine.md) | |
| [`FileCacheEngine`](./classes/Arkit/Core/Persistence/Cache/FileCacheEngine.md) | |
| [`MemcacheCacheEngine`](./classes/Arkit/Core/Persistence/Cache/MemcacheCacheEngine.md) | |
| [`MemcachedCacheEngine`](./classes/Arkit/Core/Persistence/Cache/MemcachedCacheEngine.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CacheInterface`](./classes/Arkit/Core/Persistence/Cache/CacheInterface.md) | Interface for cache engine|



### \Arkit\Core\Persistence\Client

#### Classes

| Class | Description |
|-------|-------------|
| [`Cookie`](./classes/Arkit/Core/Persistence/Client/Cookie.md) | |
| [`CookieStore`](./classes/Arkit/Core/Persistence/Client/CookieStore.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CookieInterface`](./classes/Arkit/Core/Persistence/Client/CookieInterface.md) | Interface for a value object representation of an HTTP cookie.|



### \Arkit\Core\Persistence\Database




#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`Model`](./classes/Arkit/Core/Persistence/Database/Model.md) | Abstraction for a core class for database connection|



### \Arkit\Core\Persistence\Server

#### Classes

| Class | Description |
|-------|-------------|
| [`Session`](./classes/Arkit/Core/Persistence/Server/Session.md) | Class Session|




### \Arkit\Core\Security

#### Classes

| Class | Description |
|-------|-------------|
| [`Crypt`](./classes/Arkit/Core/Security/Crypt.md) | Class for handle cryptography|




### \Arkit\Core\Security\Crypt

#### Classes

| Class | Description |
|-------|-------------|
| [`CryptInterface`](./classes/Arkit/Core/Security/Crypt/CryptInterface.md) | |
| [`OpensslCryptProvider`](./classes/Arkit/Core/Security/Crypt/OpensslCryptProvider.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CryptProviderInterface`](./classes/Arkit/Core/Security/Crypt/CryptProviderInterface.md) | Interface to define a cryptographic provider|



### \Arkit\Helper\Access

#### Classes

| Class | Description |
|-------|-------------|
| [`AccessControlHelper`](./classes/Arkit/Helper/Access/AccessControlHelper.md) | |
| [`AccessHash`](./classes/Arkit/Helper/Access/AccessHash.md) | |
| [`AccessTree`](./classes/Arkit/Helper/Access/AccessTree.md) | |




### \Arkit\Helper\Files

#### Classes

| Class | Description |
|-------|-------------|
| [`FilesManager`](./classes/Arkit/Helper/Files/FilesManager.md) | Class FilesManager|




### \Arkit\Helper\View

#### Classes

| Class | Description |
|-------|-------------|
| [`ViewFlashMemory`](./classes/Arkit/Helper/View/ViewFlashMemory.md) | Class to store flash values in session|




### \Arkit\Services\Email

#### Classes

| Class | Description |
|-------|-------------|
| [`EmailDispatcher`](./classes/Arkit/Services/Email/EmailDispatcher.md) | |




### \Arkit\Services\Payment

#### Classes

| Class | Description |
|-------|-------------|
| [`Checkout`](./classes/Arkit/Services/Payment/Checkout.md) | |




### \Arkit\Services\Pdf

#### Classes

| Class | Description |
|-------|-------------|
| [`PdfBuilder`](./classes/Arkit/Services/Pdf/PdfBuilder.md) | Class for building a pdf from a web page|




***
> Automatically generated on 2023-12-13
