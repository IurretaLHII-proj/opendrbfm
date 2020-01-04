# OpenDRBFM
OpenDRBFM is a free implementation of Toyota's DRBFM (Design Review Based on Failure Mode) method.
This application was developed by Jon Iturriondobeitia, teacher from Iurreta LHII vocational school in Iurreta, Basque Country.
## Installation
This software can be used as one site or multiple sites on one server.
The application uses a variety of tools. Therefore, installation is complex.
Tools used:
- Apache
- MySQL
- PHP
- Composer
- Zen Framework
- jQuery
- bootstrap
- Node.js
- Angular

This installation procedure is verified by Ubuntu 16.04 Xenial Linux system.

For example, Our system is:

Domain: EXAMPLE.COM

Server: drbserver.EXAMPLE.COM

Server IP: 192.168.3.218

Site: machining.drbfm.EXAMPLE.COM

### 1- Create a new site on an intranet or Internet DNS domain server.
And aim this server registers at our opendrbfm server IP address.

drbserver          IN A 192.168.3.218

machining.drbfm    IN CNAME drbserver


### 2- Install one Ubuntu Linux 16.04 (Xenial) system as usual and upgrade.
sudo apt update && apt upgrade

### 3- Adapt /etc/hosts file and check next line:
127.0.1.1 YOURHOSTNAME.drbfm.YOURDOMAIN YOURHOSTNAME

### 4- Check network configuration. As example:
auto ens18
iface ens18 inet static
    address 192.168.3.218
    network 192.168.3.0
    netmask 255.255.255.0
    broadcast 192.168.3.255
    gateway 192.168.3.1
    dns-search drbfm.YOURDOMAIN
    dns-nameservers 192.168.3.12


### 5- Create the opendrbfm user and add to sudo group.
sudo useradd -m opendrbfm -s /bin/bash -G sudo


### 6- Change his/her password
sudo passwd opendrbfm


### 7- Login as opendrbfm
exit
ssh opendrbfm@YOURHOSTNAME.drbfm.YOURDOMAIN


### 8- Create ODRBFM directory
mkdir ODRBFM
cd ODRBFM


### 9- Install the web server (apache2), PHP (7.0) php modules and the database (mysql) and Git!!!
sudo apt install apache2 php libapache2-mod-php php-mysql php-gd php-dom php-mbstring php-zip mysql-server mysql-client git
sudo apt install php-mysql php-gd php-dom php-mbstring php-zip php-curl php-xml php-xmlrpc  php-xml-htmlsax3 php-xml-parser php-xml-rpc2 php-xml-serializer php-xml-svg php-cache-lite php-http-request2 php-net-url2 php-pear php7.0-fpm php7.0-readline

sudo service apache2 restart


### 10- Enable some apache2 modules
sudo a2enmod rewrite socache_shmcb ssl

sudo service apache2 reload


### 11- Create opendrbfm site (machining) database. (I suppose database user is 'drbfm' and password is 'PASSWORD'. Change to meet your needs).

mysql -u root -p
> CREATE DATABASE IF NOT EXISTS `drbfm_machining` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
> CREATE USER 'drbfm'@'localhost'IDENTIFIED BY 'PASSWORD';
> GRANT ALL PRIVILEGES ON drbfm_machining.* TO 'drbfm'@'localhost';
> FLUSH PRIVILEGES;
> quit;


### 12- From ~/ODRBFM directory, get the software from github.com
cd ~/ODRBFM/
git clone https://github.com/IurretaLHII-proj/opendrbfm.git


### 13- Change directory name to our site name (machining).
mv opendrbfm machining


### 14- Change data and images directories group to www-data.
sudo chgrp www-data ~/ODRBFM/machining/data
sudo chgrp www-data ~/ODRBFM/machining/public/img


### 15- Create database scheme
> change database name in the file machining/sql/structure.sql (USE 'DATABASENAME'; >>> USE 'drbfm_machining'
sed -i s/DATABASENAME/drbfm_machining/ ~/ODRBFM/machining/sql/structure.sql
> load database structure into database
mysql -u drbfm -pPASSWORD < machining/sql/structure.sql


### 16- Install Composer
cd ~/ODRBFM/machining
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install


### 17- Install nvm
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.34.0/install.sh | bash


### 18- Logout and login to load .bashrc, and go to site
check nvm version
nvm --version
cd /ODRBFM/machining

### 19- Install nodejs stable version
nvm install stable
nvm use stable


### 20- Install js dependencies
cd public/js/
npm install



### 21- Create database connection settings file

echo "
<?php
return [
    'host' => 'localhost',
    'user' => 'drbfm',
    'password' => 'PASSWORD',
    'dbname' => 'drbfm_machining',
    'charset' => 'utf8',
];
" > ~/ODRBFM/machining/config/autoload/ddbb.php


### 22- Create apache2 virtual server
>> First: create link to site:
sudo mkdir /var/www/html/ODRBFM
sudo    ln -s /home/opendrbfm/ODRBFM/machining/public /var/www/html/ODRBFM/machining

>> Second: create Apache configuration
Create /etc/apache2/sites-available/001-machining.conf file with this content, and change YOURDOMAIN to your convenience:
To make this operation is not sufficient with 'sudo'. You must be root.
sudo su -


sudo echo "
<VirtualHost *:80>
    ServerName machining.drbfm.YOURDOMAIN
    
    DocumentRoot /var/www/html/ODRBFM/machining
    DirectoryIndex index.php

    <Directory /var/www/html/ODRBFM/machining>
        AllowOverride All
        Options FollowSymlinks
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>

    AddDefaultCharset utf-8

    ErrorLog ${APACHE_LOG_DIR}/drbfm.machining.error.log
    CustomLog ${APACHE_LOG_DIR}/drbfm.machining.access.log vhost_alias_combined
</VirtualHost>
" > /etc/apache2/sites-available/001-machining.conf

Go out of root role.
exit

>> Third: Activate virtual server.
sudo a2ensite 001-machining
sudo service apache2 reload


### 23- After installation there is only one user, the administrator user of the system.

Username: admin
Password: password




NOTE: CHANGE THE DEFAULT PASSWORDS!
* Get [composer](https://getcomposer.org/) and install PHP dependencies (**composer.json**)
* Get [last version of nodejs](https://github.com/nvm-sh/nvm) and install javascript dependencies with [npm](https://www.npmjs.com/) (**package.json**)


## --------------- OLD --------------Create database

CREATE DATABASE `DATABASENAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

GRANT ALL PRIVILEGES ON DATABASENAME.* TO 'USERNAME'@'localhost' IDENTIFIED BY 'USERPASSWORD';

run db **structure.sql** script located at **sql** directory and replace DATABASENAME with yours.

```
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
