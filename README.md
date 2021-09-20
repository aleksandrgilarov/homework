# Requirements

* symfony cli
* php 8
* posgres database
* composer

# How to run localy

```
git clone https://github.com/aleksandrgilarov/homework.git
copy .env.dist to .env and enter your api keys and change DB connection string
```
cd to the project directory
```
composer install
```
```
php bin/console doctrine:migrations:migrate
```
```
symfony server:start
```
Open http://127.0.0.1:8000/
http://127.0.0.1:8000/nocache for not cached data 
