Stations and services
=====================

Once you have registered a Bundle as a `Knp\MinibusBundle\Bundle\MinibusBundle`
instance you can easily create a station thanks to this convention:


All stations must be located under `BundleNamespace\Station` and implement
`Knp\Minibus\Station`.


## Auto registration

Every station located under the namespace `BundleNamespace\Station` is
automatically registered as a **station service**. To start using a station
inside your routing file you must follow this convention:

A station located under `BundleNamespace\Station\SomeStation` will be
called `bundle_alias.some` inside the routing file (where the bundle alias is
conventionaly the bundle name witheout namespace or the
`bundle::getContainerExtension()::getAlias()`).


A station is always registered as a service but the alias is sensibly different
than the routing one. For example a station located under
`BundleNamespace\Station\SomeStation` will be declared as a service with the
alias `bundle_alias.station.some`.

These are some examples of basic stations naming:

| Station namespace                         | Station alias        | Station service id           |
| ----------------------------------------- | -------------------- | ---------------------------- |
| `Vendor\MyBundle\Station\Test`            | `vendor_my.test`     | `vendor_my.station.test`     |
| `Vendor\MyBundle\Station\TestStation`     | `vendor_my.test`     | `vendor_my.station.test`     |
| `Vendor\MyBundle\Station\Sub\TestStation` | `vendor_my.sub.test` | `vendor_my.station.sub.test` |

## Register a station manually

You can register a station manually inside the container by declaring it as a
service, hence, you are free to call it as you want. However, a good practice is to
respect the convention listed above.

```yaml
# services.yml
services:
    vendor_my.station.test:
        class: Vendor\MyBundle\Station\Test
        arguments: [@some_service]
        tags:
            # You must specify the alias!
            - { name: knp_minibus.station, alias: vendor_my.test }
```

## Some good practices

### What about retrieving the `Request` ?

Basically when a station handles a `Minibus`, the given minibus is an instance of
`Knp\Minibus\Http\HttpMinibus` so you can easily retrieve `Request` and `Response`:

```php
$minibus->getRequest();
$minibus->getResponse();
```

**NOTE**: This is not a good practice to use directly the request, but sometimes it can be convenient.
See the next chapter about routing to get ideas on how to retrieve
request informations without injecting it.

### What is a *good* station ?

A good station is a station that respects those two simple rules:

- **Do only one thing**
- **Meaningful**

Here are some examples:

| Good one                               | Bad one                                                                 |
| -------------------------------------- | ----------------------------------------------------------------------- |
| `CreateUserStation`                    | `UserStation`                                                           |
| `CreditProductStation`                 | `ProductStation`                                                        |
| `HandleProductSearchFormStation`       | `SearchFormStation` (even `ProductSearchFormStation` is not that good)` |
| `GiveMeTheTrendingProductPriceStation` | `TrendingProductStation`                                                |

Prefer to use long but **meaningful** names and, of course remember you can also use namespaces.

## Debugging stations

You can list all available stations and their configuration with the following
command:

```shell
$ php app/console knp_minibus:station:dump-reference # Lists all the stations.
$ php app/console knp_minibus:station:dump-reference <station name> # Displays a station detail.
```

## Next part ?

If you are okay with stations, let's talk about [routing](routing.md)!
