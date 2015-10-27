# You shall not pass - examples

### Installation

Each example is a standalone app. You only need to run `composer install` in it's main directory and configure required parameters. All examples require `mysql database` and `php 5.5+` installed. For example 4 you also need to have configured `Facebook connect` app.

### Examples

- uszanowanko-1  - basic non-secured app
- uszanowanko-2 - app with basic form security
- uszanowanko-3 - app with voters as access control
- uszanowanko-4 - custom provider for facebook authentication
- uszanowanko-5 - using listener to provider two-factor authentication

### Running

- run `composer install`
- install fixtures by running `php app/console doctrine:fixtures:load`
- run php server by 'php app/console server:start'
