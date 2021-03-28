# Server component

This directory contains the server part of Chauffe-Marcel. It's a simple
Symfony application.

Its role is to receive and store configuration received through its API and
send commands to the Particle Photon.

See the [swagger ui](http://petstore.swagger.io/?baseurl=https://raw.githubusercontent.com/pyrech/chauffe-marcel/master/config/openapi.json)
of the API. You will not be able to test requests as the host of the API is not
provided in the [openapi.json](`../config/openapi.json`).

## Modes

The server exposes 3 modes that can be set through the API:

- `forced_on`, heating controller forced on `NORMAL heating`
- `forced_off`, heating controller forced on `REDUCED heating`
- `not_forced`, server will determine the correct mode according to the configured time slots.

## Time slots

Time slots can be configured through the API to determine when the heating
controller should be on `NORMAL heating`.

A time slot has a start and end hours, the day of the week it applies and a
UUID (for editing/removing purposes).

If mode is set to `not_forced` and no time slot matches the current date and
time, then the heating controller will be set to `REDUCED heating`. This
behavior is provided by the command `php bin/console chauffe_marcel:periodic`
running regularly.

## Requirements

- PHP 7.4
- Web server

## Installation

Use [`composer`](https://getcomposer.org/) to install the application.

```bash
composer install
```

## Configuration

- The web server should be configured with its root directory pointing to `public/`
- A cron should regularly (ie every 5 minutes is fine) run `php bin/console chauffe_marcel:periodic`
