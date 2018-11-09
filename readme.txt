Pre-requis

Install git docker docker-compose composer

Installation


sudo mkdir /home/workirs
cd /home/workirs

git clone https://github.com/davidiliou/shopping_cart.git test_symfony

cd sf4

sudo docker-compose build

sudo docker-compose up -d

sudo docker-compose up exec php bash

composer install
yarn install
yarn encore dev

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

Navigate on http://localhost:8080/home

