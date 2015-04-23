Routing in details
==================

Minibus bundle comes with a new yaml routing syntax.

## Import a minibus routing

In order to create minibus routes you just have to import a minibus routing
file:

```yaml
# app/config/routing.yml
my_bundle:
    resource: "@MyBundle/Resources/config/routing.yml"
    type: minibus
    prefix: /
```

You can import as many routing files you want.

## The basics

In order to create a route you must specify some basics:

- A `pattern`
- A `line` (a succession of stations with optional configuration)
- A `terminus` (defines how the `Minibus` will be rendered at the end)

This is a basic example:

```yaml
# src/MyBundle/Resources/config/routing.yml
some_route:
    pattern: /some/route
    line:
        my_bundle.fetch_something: ~
        my_bundle.handle_some_form:
            config_key: config_value
        my_bundle.clear_something: ~
    terminus:
        twig:
            template: "MyBundle::some_template.html.twig"
```

This routing file will create a route called `some_route` that will launch the
following stations:

1. `my_bundle.fetch_something`
2. `my_bundle.handle_some_form` with the configuration `{"config_key": "config_value"}`
3. `my_bundle.clear_something`

Eventually, this route will end by a **twig template** located under
`src/MyBundle/Resources/views/some_template.html.twig`. This template can
access to **all the passengers defined in the minibus**. for example if the
station `my_bundle.fetch_something` registers a passenger `something` in the
minibus, you can easily access this passenger in twig with `{{ something }}`.


A complete **terminus reference** can be found in the next chapter of this
documentation ;).

## Define default passengers

This routing syntax comes with a *default passenger resolver*. It means that
before your station is launched, you can specify some default passengers for
your line:

```yaml
# src/MyBundle/Resources/config/routing.yml
some_route:
    pattern: /some/route
    line:
        my_bundle.fetch_something: ~
        my_bundle.handle_some_form:
            config_key: config_value
        my_bundle.clear_something: ~
    terminus:
        twig:
            template: "MyBundle::some_template.html.twig"
    passengers:
        plop: "Plip"
```

Here you can access the `plop` passenger easily in your station.
Just ask the minibus: `$minibus->getPassenger('plop')`.

## Resolve default passengers

Default passengers can be **resolved** from a **service call** or a
**class instanciation**.

```yaml
# src/MyBundle/Resources/config/routing.yml
product_show:
    pattern: /product/{id}
    line:
        my_bundle.prepare_product: ~
        my_bundle.retrieve_basket: ~
    terminus:
        twig:
            template: "MyBundle::product_show.html.twig"
    passengers:
        some_service:
            # will just inject the service
            service: some_service
        product:
            service: my_bundle.product_fetcher
            method: fetch
            # use the special "$" to reference a route parameter.
            arguments: [$id, @some_service, %some_parameter%, "raw value"]
        basket:
            class: MyBundle\Basket
            # you can reference a passenger defined before with "$" too.
            arguments: [$product]
        discount_basket:
            class: MyBundle\Basket
            # will call the static constructor : MyBundle\Basket::createDiscount
            method: createDiscount
            arguments: [$product]
```

<p align="center">
    <img src="../.images/notbad.jpg" alt="not bad" />
</p>

## Routing reference

You can use the following keys inside a minibus routing:

```yaml
some_route_reference:
    # the route pattern
    pattern: /some/pattern
    # method separated by |
    method: GET|POST
    # default format
    format: html
    # route condition
    # see http://symfony.com/doc/current/book/routing.html#book-routing-conditions
    condition: ~
    # route host
    # see http://symfony.com/doc/current/components/routing/hostname_pattern.html
    host: some.host
    # route scheme
    # see http://symfony.com/doc/current/cookbook/routing/scheme.html
    scheme: https
    requirements: ~
    defaults: ~
    line: ~
    terminus: ~
    passengers: ~
```

## What next ?

Next section is about [terminus](terminus.md)
