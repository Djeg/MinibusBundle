Understand terminus
===================

A terminus is what **end** a line. The role of a terminus is to transform minibus
passengers into something else.

## Existing terminus

By default minibus comes with some basic terminus:

- `twig` display a twig template by passing the minibus passenger.
- `serialize` serialize minibus pasenger.
- `redirect` redirect to a given target.
- `redirect_to_route` redirect to a given route.

Each terminus can receive configurations.
