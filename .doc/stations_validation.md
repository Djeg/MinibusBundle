Be safe, validate your stations
===============================

At some point, you will create stations that **care** about wich passenger
is present when the minibus arrive or leave your station. You can easily
defined the **entering** and **leaving** passengers of your stations with
those two interfaces:

- `Knp\Minibus\Expectation\ResolveEnteringPassengers`
- `Knp\Minibus\Expectation\ResolveLeavingPassengers`

## Defined the expected entering passengers

If you want to define wich passenger should be present when the minibus enter
in your stations you just have to implement the interface:

```php
namespace My\Bundle\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knp\Minibus\Expectation\ResolveEnteringPassengers;

class SomeStation implements Station, ResolveEnteringPassengers
{
    public function setEnteringPassengers(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['cat'])
            ->setAllowedType(['cat' => 'Some\Cat'])
        ;
    }

    public function handle(Minibus $minibus, array $configuration)
    {
        // here you use the cat safely!
        $cat = $minibus->getPassenger('cat');
    }
}
```

# Defined the expected leaving passengers

Like the entering one, you can defined wich passengers must leave or not
your minibus.

```php
namespace My\Bundle\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knp\Minibus\Expectation\ResolveLeavingPassengers;

class SomeStation implements Station, ResolveLeavingPassengers
{
    public function setLeavingPassengers(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['cat'])
            ->setAllowedType(['cat' => 'Some\Cat'])
        ;
    }

    public function handle(Minibus $minibus, array $configuration)
    {
        // here, if you don't defined the cat passenger has an instance
        // of Some\Cat your station will failed \o/
        $minibus->addPassenger('cat', new \Some\Cat('garfield'));
    }
}
```

## Defined expected leaving and entering passengers

You can also validate both passengers, leaving and entering by implementing
the two interfaces:

```php
namespace My\Bundle\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knp\Minibus\Expectation\ResolveLeavingPassengers;
use Knp\Minibus\Expectation\ResolveEnteringPassengers;

class SomeStation implements Station, ResolveLeavingPassengers, ResolveEnteringPassengers
{
    public function setLeavingPassengers(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['cat'])
            ->setAllowedType(['cat' => 'Some\Cat'])
        ;
    }

    public function setEnteringPassengers(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['food'])
            ->setAllowedType(['food' => 'Some\CatFood'])
        ;
    }

    public function handle(Minibus $minibus, array $configuration)
    {
        // here you can use the food passenger safely
        $food = $minibus->getPassenger('food');

        $cat = new \Some\Cat('garfield');
        $cat->feedWith($food);

        // here, if you don't defined the cat passenger has an instance
        // of Some\Cat your station will failed \o/
        $minibus->addPassenger('cat', $cat);
    }
}
```

## Keep continue !

Let's continue and visit the [next part](events.md)
