Programming Challenge
===

## Installing

### Install composer
    composer install -o
    
### Create database
    bin/console doctrine:database:create
    
### Execute DB migrations
    bin/console doctrine:migrations:migrate
    
### Command to parse data from site
    bin/console soccer:fetch-dat