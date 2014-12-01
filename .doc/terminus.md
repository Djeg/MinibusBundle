Understand terminus
===================

A terminus is what **end** a line. The role of a terminus is to transform minibus
passengers into something else.

## Specify a terminus

When you write a route you can sepcify a terminus that will display the minibus
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

- `twig` display a twig template by passing the minibus passenger.
- `jms_serializer` serialize minibus pasengers with the [jms serializer](http://jmsyst.com/libs/serializer).
- `redirect` redirect to a given target.
- `redirect_to_route` redirect to a given route.
- `twig_response` display a twig template and can configure response attributes.
- `jms_serializer_response` same as the `jms_serializer` but the response attributes can be configured.

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

You can easily create a terminus but respecting the following conventions:

- A terminus must be located under `BundleNamespace\Terminus`.
- A terminus must implement `Knp\Minibus\Terminus`

This is a basic terminus that display a cat name if it exists:

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
            throw new NotFoundHttpException('No cutty cat has been found :-(');
        }

        $response = new Response;
        $response->setContent((string)$minibus->getPassenger('cat'));
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
```

This terminus will be auto register into with the name `my_bundle.cat` and is
ready to use!

## Register a terminus manualy

If you want to register a terminus manualy as a service, this is not a problem:

```yaml
services:
    cat_terminus:
        class: My\Bundle\CatTerminus
        tags:
            # The alias will defined how the terminus is named inside your
            # routing file.
            - { name: knp_minibus.terminus, alias: my_bundle.cat }
```

## Go further ?

Do not stop there! Let's go [next](stations_validation.md).
