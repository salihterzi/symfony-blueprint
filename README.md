# symfony-blueprint
* Symfony 6.1
* Angular 14.1.0

To run the project for development use "docker-compose up". If you want to force image to build use "docker-compose up --build"

To create a new database:
```
php bin/console doctrine:database:create
```
 To migrate changes to database:
```
php bin/console doctrine:migrations:migrate
```
 To load sample data to database:
```
php bin/console doctrine:fixtures:load
```
 To make this changes for Test database add "--env=test" to above commands
