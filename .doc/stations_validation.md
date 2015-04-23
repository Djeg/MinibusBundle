Drive safely, validate your stations
===============================

At some point, you will create stations that **care** about which passenger
is present when the minibus enters or leaves your station. You can easily
define the **entering** and **leaving** passengers of your stations with
those two interfaces:

- `Knp\Minibus\Expectation\ResolveEnteringPassengers`
- `Knp\Minibus\Expectation\ResolveLeavingPassengers`

## Define the expected entering passengers

If you want to define wich passengers should be present when the minibus enters
your stations you just have to implement the interface:

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

    public function handle(Minibus $minibus, array $configuration = [])
    {
        // here you can use the cat safely!
        $cat = $minibus->getPassenger('cat');
    }
}
```

# Define the expected leaving passengers

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

    public function handle(Minibus $minibus, array $configuration = [])
    {
        // here if you did not define the cat passenger as an instance
        // of Some\Cat your station will fail \o/
        $minibus->addPassenger('cat', new \Some\Cat('garfield'));
    }
}
```

## Define expected leaving and entering passengers

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

        // here, if you don't define the cat passenger as an instance
        // of Some\Cat your station will fail \o/
        $minibus->addPassenger('cat', $cat);
    }
}
```

## Keep going!

Let's visit the [next part](events.md).
