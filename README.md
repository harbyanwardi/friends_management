<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>


Author : Harby Anwardi

## About Laravel-Docker
build docker

Copy file .env.example to .env

docker-compose up -d

The following command will generate a key and copy it to your .env file, ensuring that your user sessions and encrypted data remain secure:
docker-compose exec app php artisan key:generate

You now have the environment settings required to run your application. To cache these settings into a file, which will boost your application’s load speed, run:
docker-compose exec app php artisan config:cache

To create a new user, execute an interactive bash shell on the db container with docker-compose exec:
docker-compose exec db bash

Inside the container, log into the MySQL root administrative account:
mysql -u root -p

password root : qwerty1234

mysql> show databases;

mysql> ALTER USER 'sequise'@'%' IDENTIFIED WITH mysql_native_password BY 'qwerty1234';

mysql> FLUSH PRIVILEGES;

mysql> EXIT;

Finally, exit the container:
exit

First, test the connection to MySQL by running the Laravel artisan migrate command, which creates a migrations table in the database from inside the container:
docker-compose exec app php artisan migrate

use seeder to fill table
docker-compose exec app php artisan db:seed --class=UsersTableSeeder

OPEN APPLICATION HOST
localhost:88

OPEN PHPMYADMIN
http://localhost:7000/
user : sequise
password : qwerty1234
