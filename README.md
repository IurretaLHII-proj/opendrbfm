# OpenDrbfm

## Installation

* Get [composer](https://getcomposer.org/) and install PHP dependencies (**composer.json**)
* Get [last version of nodejs](https://github.com/nvm-sh/nvm) and install javascript dependencies with [npm](https://www.npmjs.com/) (**package.json**)

## Configuration

Create ddbb.php file inside /config/autoload directory and type inside the credentials to connect the database.

```php
return [
  'host' => '',
  'user' => '',
  'password' => '',
  'dbname' => 'drbfm',
  'charset' => 'utf8',
];
```
run db structure script

```php
mysql -h 'host' -u 'user' -p'password' < /sql/structure.sql
```

## Demo

Log in [https://dev.opendrbfm.iurretalhi.eus](https://dev.opendrbfm.iurretalhi.eus) with

* user: demouser
* password: demouser

> This software needs a complete refactory.
> Currently the frontend and backend works together without a serverless api, and both needs know each other.
> Should be separated and updated each angular and zend frameworks to last versions.
> Resolver bugs and write tests.
