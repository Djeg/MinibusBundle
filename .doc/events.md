Hack the minibus with events
============================

If for some reason you are interested in hacking your minibus line, you can easily do
it with events.

The minibus events are documented [here](https://github.com/Djeg/Minibus/blob/master/.doc/deal_with_events.md).

## Register minibus listener/subscriber

To register a minibus event first create a listener and/or a subscriber (here
we just show a listener):

```php
namespace My\Bundle\EventListener;

use Knp\Minibus\Event\StartEvent;

class DefineMinibusInspector
{
    public function onLineStart(StartEvent $event)
    {
        $event
            ->getMinibus()
            ->addPassenger('inspector', new \Minibus\Inspector)
        ;
    }
}
```

Finally you just have to register it as a service:

```yaml
services:
    define_minibus_inspector:
        class: My\Bundle\EventListener\DefineMinibusInspector
        tags:
            - { name: kernel.event_listener, event: knp_minibus.start, method: onLineStart }
```

That's it! Every station in your minibus will get an `inspector` passenger!

## What next?

Last section is about [configuration](configurable_stations_and_terminus.md).
