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

`curl -H "X-AUTH-TOKEN: REAL" -X GET localhost/api/user/1`

2) Set user:

`curl -H "X-AUTH-TOKEN: REAL" -X POST "localhost/api/user/new?fullName=jan&username=jan_kowalski&email=jan.kowalski@email.pl&password=jan&plainPassword=jan"`



