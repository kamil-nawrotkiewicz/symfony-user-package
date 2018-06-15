# Symfony User Package

In 1.0 package represent only basic possibilities. Foundation of this package is the creation of a code for the fast deployment of users and their permissions. This software will be including advanced solutions.  

## Installation Guide

#### In the main project directory, run the following commands

1. Make environment file .env and set database connection:
```
# Database Connection

 DATABASE_URL=postgresql://user:password@localhost/database-name
```

2) `composer install`

3) `npm install`

4) `bower install`

5) `gulp`

5) `php bin/console doctrine:migrations:migrate`

6) `php bin/console doctrine:fixtures:load`

7) `Log in using: username(admin), password(admin)`

## How using API

#### Examples

1) Get users:

`curl -X GET "localhost/api/user/1” -H "accept: application/json"`

2) Set user:

`curl -X POST "localhost:82/api/user/new?fullName=kamil&username=kamil&email=kamil.nawrotkiewicz@icloud.com&password=kamil" -H "accept: application/json"`



