
# Shopping Cart

Test project with a catalog of 12 products in sqlite database and a shopping cart stored in session

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Install git docker docker-compose

Ubuntu : 
sudo apt install git docker docker-compose


### Installing

Create directory

`sudo mkdir /home/workirs`

Change directory

    cd /home/workirs

Get project from github

    git clone https://github.com/davidiliou/shopping_cart.git sf4

Change directory again

    cd sf4/

Build containers

    sudo docker-compose build


Launch containers

    sudo docker-compose up -d

Enter in php container to install symfony project

    sudo docker-compose exec php bash


Change directory again

    cd sf4/

Build symfony project

    composer install

install Assets

    yarn install


Build assets

    yarn encore dev


Create sqlite database

    php bin/console doctrine:database:create


Migrate ( create table )

    php bin/console doctrine:migrations:migrate


Load fixtures 12 products

    php bin/console doctrine:fixtures:load


Navigate on
    
    http://localhost:8080/home

Export products in csv file :

    php bin/console app:export-product-csv
    
Api point to get all users in json:
    
    http://localhost:8080/api/v1/users
    

### Running the tests

To run php Unit tests :

    ./bin/phpunit
