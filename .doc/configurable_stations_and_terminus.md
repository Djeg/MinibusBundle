Create configurable stations and terminus
=========================================

You can define **configurable stations and terminus**. For example, in your
routing file:

```yaml
my_route:
    pattern: /some/route
    line:
        my_station:
            # station configuration here
            some_key: some_value
    terminus:
        my_terminus:
            # terminus configuration here
```

In order to use this configuration you just have to take a look on the second
argument of the methods inside `Knp\Minibus\Station` and `Knp\Minibus\Terminus`.

This is an example with a terminus:

```php
namespace My\Bundle\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;

class MyStation implements Station
{
    public function handle(Minibus $minibus, array $configuration = [])
    {
        $someConfig = $configuration['some_key'];
    }
}
```

## Define cute configuration tree ^.^

If you want to define a complete configuration tree for your stations
and terminus, so you can see it by using the commmand 
`php app/console knp_minibus:station:dump-reference twig` you can take
a look at the [dedicated documentation](https://github.com/Djeg/Minibus/blob/master/.doc/configure_your_stations_and_terminus.md).
