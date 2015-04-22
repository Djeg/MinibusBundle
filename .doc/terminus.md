Understand terminus
===================

A terminus is what **ends** a line. The role of a terminus is to transform minibus
passengers into something else.

## Specify a terminus

When you write a route you can specify a terminus that will display the minibus
passengers:

```yaml
my_route:
    pattern: /some/route
    line:
        some_station: ~
        other_station: ~
    terminus:
        terminus_name: ~ # specify configuration here
```

## Existing terminus

By default minibus comes with some basic terminus:

- `twig` displays a twig template and gives it the minibus passengers
- `jms_serializer` serializes minibus pasengers with the [jms serializer](http://jmsyst.com/libs/serializer)
- `jms_serializer_response` (same as the `jms_serializer` but the response attributes can be configured)
- `redirect` redirects to a given target
- `redirect_to_route` redirects to a given route
- `twig_response` displays a twig template and can configure response attributes

## Display a terminus reference

You can easily get the list of all configured terminus by using the following
command:

```shell
# Display a list of available terminus
$ php app/console knp_minibus:terminus:dump-reference
# Display a specific terminus reference
$ php app/console knp_minibus:terminus:dump-reference <terminus name>
```

## Create your own terminus

You can easily create a terminus by respecting the following conventions:

- A terminus must be located under `BundleNamespace\Terminus`.
- A terminus must implement `Knp\Minibus\Terminus`

This is a basic terminus that displays a cat name if it exists:

```php
namespace My\Bundle\Terminus;

use Knp\Minibus\Terminus;
use Knp\Minibus\Minibus;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class CatTerminus implements Terminus
{
    public function terminate(Minibus $minibus, array $configuration)
    {
        if (!$minibus->hasPassenger('cat')) {
            throw new NotFoundHttpException('No kitty has been found :-(');
        }

        $response = new Response;
        $response->setContent((string)$minibus->getPassenger('cat'));
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
```

This terminus is auto registered with the name `my_bundle.cat` and is
ready to use!

## Register a terminus manually

If you want to manually register a terminus as a service, this is not a problem:

```yaml
services:
    cat_terminus:
        class: My\Bundle\CatTerminus
        tags:
            # The alias will define how the terminus is named inside your
            # routing file.
            - { name: knp_minibus.terminus, alias: my_bundle.cat }
```

## Want to go further?

Keep going! Let's go [next](stations_validation.md).
