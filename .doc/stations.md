Stations and services
=====================

Once you have registered a Bundle has as a `Knp\MinibusBundle\Bundle\MinibusBundle`
instance you can easily create station by following this convention:


All stations should be located under `BundleNamespace\Station` and must implements
`Knp\Minibus\Station`.


## Auto registration

All the station located under the namespace `BundleNamespace\Station` is
automatically registered as a **station service**. To start using a station
inside your routing file you must follow this convention:

A station located under `BundleNamespace\Station\SomeStation` will be
called `bundle_alias.some` inside the routing file (where the bundle alias is
conventionaly the bundle name witheout namespace or the
`bundle::getContainerExtension()::getAlias()`).


A station is alays registered as a service but the alias is sensibly different
than the routing one. For example a station located under
`BundleNamespace\Station\SomeStation` will be declared as a service with the
alias `bundle_alias.station.some`.

This is some exemple of basic stations naming:

| Station namespace                         | Station alias        | Station service id           |
| ----------------------------------------- | -------------------- | ---------------------------- |
| `Vendor\MyBundle\Station\Test`            | `vendor_my.test`     | `vendor_my.station.test`     |
| `Vendor\MyBundle\Station\TestStation`     | `vendor_my.test`     | `vendor_my.station.test`     |
| `Vendor\MyBundle\Station\Sub\TestStation` | `vendor_my.sub.test` | `vendor_my.station.sub.test` |

## Register a station manualy

You can regster a station manualy inside the container by declaring it as a
service but, please respect the convention listed above.

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

## Some good practice

### What about retrieve the `Request` ?

Basicaly when a station handle a `Minibus` the given minibus is an instance of
`Knp\Minibus\Http\HttpMinibus` so you can retrieve `Request` and `Response` easily:

```php
$minibus->getRequest();
$minibus->getResponse();
```

**NOTE**: This is not a good practice to use directly the request, but sometimes it's
needed :/. See the next chapter about routing to get and idea on how to retrieve
request informations witheout injecting it.

### What is a *good* station ?

A good station is a station that respect those 2 rules:

- **Do one thing**
- **Meaningfull**

This is some examples:

| Good one                               | Bad one                                                                 |
| -------------------------------------- | ----------------------------------------------------------------------- |
| `CreateUserStation`                    | `UserStation`                                                           |
| `CreditProductStation`                 | `ProductStation`                                                        |
| `HandleProductSearchFormStation`       | `SearchFormStation` (even `ProductSearchFormStation` is not that good)` |
| `GiveMeTheTrendingProductPriceStation` | `TrendingProductStation`                                                |

Prefer to use long but **meaningfull** name and, of course you can also use namespaces.

## Next part ?

If you are okay with stations, let's talk about [routing](routing.md)
