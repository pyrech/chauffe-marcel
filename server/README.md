# Server component

This directory contains the server part of Chauffe-Marcel. It's a simple
Symfony application.

Its role is to receive and store configuration from the mobile application and
send commands to the Particle Photon.

## Requirements

- PHP 7
- Web server

## Installation

Use `composer` to install the application. For development purposes, some
make tasks are also available, see `make help`.

## Configuration

- The web server should be configured with its root directory pointing to `web/`
- A cron should regularly (ie every 5 minutes is fine) run `php bin/console chauffe_marcel:periodic`
