# OpenDrbfm

## Installation

* Get [composer](https://getcomposer.org/) and install PHP dependencies (**composer.json**)
* Get [last version of nodejs](https://github.com/nvm-sh/nvm) and install javascript dependencies with [npm](https://www.npmjs.com/) (**package.json**)


## Create database
--
-- Database: `DATABASENAME`
--
CREATE DATABASE `DATABASENAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

GRANT ALL PRIVILEGES ON DATABASENAME.* TO 'USERNAME'@'localhost' IDENTIFIED BY 'USERPASSWORD';

run db **structure.sql** script located at **sql** directory
```php
mysql -h localhost -u USERNAME -pUSERPASSWORD < sql/structure.sql
```

## Configuration

Create **ddbb.php** file inside **config/autoload** directory and type inside the credentials to connect the database.

```php
<?php

return [
  'host' => 'localhost',
  'user' => 'USERNAME',
  'password' => 'USERPASSWORD',
  'dbname' => 'DATABASENAME',
  'charset' => 'utf8',
];
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
