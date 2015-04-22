MinibusBundle [![Build Status](https://travis-ci.org/Djeg/MinibusBundle.svg)](https://travis-ci.org/Djeg/MinibusBundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Djeg/MinibusBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Djeg/MinibusBundle/?branch=master) [![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Djeg/MinibusBundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)
==========================================================================================================================================================================================================================================================================================================================================================================================================================================================

<p align="center">
    <img src=".images/minibus_mini.png" alt="minibus" />
</p>

Yolo Yolo ^.^. This is an integration of [Minibus](https://github.com/Djeg/Minibus)
for Symfony2 applications.

## A Minibus, but why ?

Minibus is a standalone **PHP 5.4** library which allows you to decouple your
application responsabilities and actions into plural **stations**. The main goal
is to create **meaningful controllers** and **limit their responsabilities**. For
example with Minibus, your **application entry points** have nothing to do
with the **view**. Their responsability will only be to change and retrieve a given
**state**.

## Installation

Install the bundle:

```
$ php composer.phar require knplabs/knp-minibus-bundle
```

Update your `AppKernel`:

```php
// app/AppKernel.php
$bundles = [
    // ...
    new Knp\MinibusBundle\KnpMinibusBundle,
];
```

## Quick view

You were waiting for a demonstration, here it is!

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

Stations are like controllers, except that you can launch as many stations as you
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

MinibusBundle comes with a complete yaml routing syntax that allows you to easily
create a **line**. Once you get some stations ready to be used you can load
a minibus routing:

```yaml
# app/config/routing.yml
app:
    resource: @AppBundle/Resources/config/routing.yml
    type: minibus
    prefix: /
```

Here comes the routing:

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
            template: "App::some.html.twig"
    passengers:
        magic_man:
            service: some_service_id
            method: getMagicMan
```

## Give it a try!

That's pretty it. **Give it a try!**.

Of course if you want a complete documentation of this bundle you just have to follow
the guide!

## Get more!

- [Stations and services](.doc/stations.md)
- [The routing in details](.doc/routing.md)
- [Understand what is a terminus](.doc/terminus.md)
- [Drive safely, validate your stations](.doc/stations_validation.md)
- [Hack the minibus with events](.doc/events.md)
- [Create configurable stations and terminus](.doc/configurable_stations_and_terminus.md)
