***

# DomainRouter

Router class for domain



* Full name: `\Arkit\Core\Control\Routing\DomainRouter`




## Methods


### __construct

Constructor of the class.

```php
public __construct(array& $routerConfig): mixed
```

Router configuration should be an array where keys are the domain or subdmains and the associated values are the url routers that handle the rest of the request.
domain.com: /Doamin/_config/router.yaml

For internatinalization, the first level of the url is used.
mydomain.com:
  es : /MyDomain/_config/router.es.yaml
  en : /MyDomain/_config/router.en.yaml

So, when the request is http://mydomain.com/en/rest/of/url it takes first 'mydomain.com' and then 'en'.






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$routerConfig` | **array** | Router configuration |





***

### route

Return a url router file defintion given a request.

```php
public route(\Arkit\Core\HTTP\RequestInterface& $request): string|bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **\Arkit\Core\HTTP\RequestInterface** | Request from client |


**Return Value:**

If can handle the request, return a router file definition, false otherwise.




***


***
> Automatically generated on 2023-12-15
