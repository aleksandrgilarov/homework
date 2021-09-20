# Requirements

* Symfony CLI
* php 8
* PostgreSQL database
* composer

# How to run localy

```
git clone https://github.com/aleksandrgilarov/homework.git
```

cd to the project directory
copy .env.dist to .env and enter your api keys and change DB connection string

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
