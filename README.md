Programming Challenge
===

## Installing

### Install composer
    composer install -o
    
### Create database
    bin/console doctrine:database:create
    bin/console doctrine:database:create -e=test
    
### Execute DB migrations
    bin/console doctrine:migrations:migrate
    bin/console doctrine:migrations:migrate -e=test
    
### Run unit tests
    bin/phpunit
    
### Run behat tests
    vendor/behat/behat/bin/behat
    
### Command to parse data from site
    bin/console soccer:fetch-data