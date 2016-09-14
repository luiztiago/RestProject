# Setup

## Install project

```
git clone git@github.com:luiztiago/RestProject.git
cd RestProject
composer install
```

## Database

- Edit the db config in `app/config/parameters.yml`
- Create the database

```
composer update --optimize-autoloader
php bin/console doctrine:migrations:migrate
php bin/console server:run
```
