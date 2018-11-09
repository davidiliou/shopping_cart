# Shopping Cart

Test project with a catalog of 12 products and a shopping cart

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Install git docker docker-compose
```
Ubuntu : 
sudo apt install git docker docker-compose
```

### Installing

Create directory
sudo mkdir /home/workirs
```
Change directory
cd /home/workirs
```
Get project from github
git clone https://github.com/davidiliou/shopping_cart.git sf4
```
Change directory again
cd sf4/
```
Build containers
sudo docker-compose build
```

Launch containers
sudo docker-compose up -d
```
Enter in php container to install symfony project
sudo docker-compose exec php bash
```

Change directory again
cd sf4/
```
Build symfony project
composer install
```
install Assets
yarn install
```

Build assets
yarn encore dev
```

Create sqlite database
php bin/console doctrine:database:create
```
Migrate ( create table )
php bin/console doctrine:migrations:migrate
```

Load fixtures 12 products
php bin/console doctrine:fixtures:load
```

Navigate on
http://localhost:8080/home
```

If build in virtual machine replace localhost by ip's virtual machine ( example http://192.168.1.109:8080/home )

## Running the tests

To run php Unit tests :

./bin/phpunit
```

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```


## Authors

* **David ILIOU** - *Technical test*

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
