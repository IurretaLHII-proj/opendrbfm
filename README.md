# OpenDrbfm

## Installation

* Get [composer](https://getcomposer.org/) and install PHP dependencies (**composer.json**)
* Get [last version of nodejs](https://github.com/nvm-sh/nvm) and install javascript dependencies with [npm](https://www.npmjs.com/) (**package.json**)

## Configuration

Create **ddbb.php** file inside **config/autoload** directory and type inside the credentials to connect the database.

```php
<?php

return [
  'host' => '',
  'user' => '',
  'password' => '',
  'dbname' => 'drbfm',
  'charset' => 'utf8',
];
```
--
-- Database: `drbfm`
--
CREATE DATABASE `DATABASENAME` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

GRANT ALL PRIVILEGES ON DATABASENAME

USE `DATABASENAME`;



run db **structure.sql** script located at **sql** directory

```php
mysql -h 'host' -u 'user' -p'password' < sql/structure.sql
```
## Permissions

Give **write permissions to Apache** on following directories

* /data
* /public/img

## Demo

Log in [https://opendrbfm.iurretalhi.eus](https://opendrbfm.iurretalhi.eus) with

* user: Demo
* password: demouser

> This software needs a complete refactory.
> Currently the frontend and backend works together without a serverless api, and both needs know each other.
> Should be separated and updated each angular and zend frameworks to last versions.
> Resolver bugs and write tests.
