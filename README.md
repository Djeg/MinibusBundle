MinibusBundle [![Build Status](https://travis-ci.org/Djeg/MinibusBundle.svg)](https://travis-ci.org/Djeg/MinibusBundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Djeg/MinibusBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Djeg/MinibusBundle/?branch=master) [![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Djeg/MinibusBundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)
==========================================================================================================================================================================================================================================================================================================================================================================================================================================================

<p align="center">
    <img src=".images/minibus_mini.png" alt="minibus" />
</p>

Yolo Yolo ^.^. This is an integration of [Minibus](https://github.com/Djeg/Minibus)
for Symfony2 applications.

## A Minibus, but why ?

Minibus is a standalone **PHP 5.4** library that allowing you to decouple your
applications responsability and actions into plural **stations**. The main goal
is to create **meainingfull controller** and **limit their responsability**. For
example With Minibus, your **application entry points** has nothing to do
with **view**. Their responsability will just be to change, retrieve a given
**state**.

## Installation

Install the bundle:

```
$ php composer.phar require knplabs/knp-minibus-bundle
```

Update your `AppKernel`:

```php
$bundles = [
    // ...
    new Knp\MinibusBundle\KnpMinibusBundle,
];
```

## Quick view

You was waiting for a demonstration, here we are!

### Create a MinibusBundle

In order to start working with Minibus you need to create your first `MinibusBundle`:

```php
namespace App;

use Knp\MinibusBundle\Bundle\MinibusBundle;

class AppBundle extends MinibusBundle
{
}
```

Now register it inside your `AppKernel`:

```php
// app/AppKernel.php
$bundles = [
    // ...
    new App\AppBundle,
];
```

### Create stations

Stations are like controllers, except that you can launch as many stations you
want during one **action** and the station **does not handle any kind of view**.
In fact the only role of a station is to create/retrieve/update `Minibus` passengers.
(understand data).


Station **must** be located under `BundleNamespace\Station` and implements
`Knp\Minibus\Station`. This is a station example:

```php
namespace App\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;

class UsernameStation implements Station
{
    public function handle(Minibus $minibus, array $configuration = [])
    {
        $minibus->addPassenger('username', 'Sheldon Cooper');
    }
}
```

### Routing

MinibusBundle comes with a complete yaml routing syntax that allow you to easily
create **line**. Once you get some stations ready to be used you can load
a minibus routing:

```yaml
# app/config/routing.yml
app:
    resource: @AppBundle/Resources/config/routing.yml
    type: minibus
    prefix: /
```

Here came the routing:

```yaml
app_some_action:
    pattern: /some/action
    method: GET
    line:
        app.username: ~
        app.other: ~
        app.magic: ~
    terminus:
        twig:
            template: "AppBundle::some.html.twig"
    passengers:
        magic_man:
            service: some_service_id
            method: getMagicMan
```

## Give it a try!

Last word before many lines of documentation : **Give it a try!**. Of course
if you want a complete documentation of this bundle you just have to follow
the guide!

## Get more!

- [stations and services](.doc/stations.md)
- [The routing in details](.doc/routing.md)
- [Understand terminus](.doc/terminus.md)
- [be safe, validate your stations](.doc/stations_validation.md)
- [Hack the minibus with events](.doc/events.md)
- [Create configurable stations and terminus](.doc/configurable_stations_and_terminus.md)
