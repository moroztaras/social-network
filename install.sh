#!/usr/bin/env bash
clear

echo "1 - install"
echo "2 - import SQL file to Database"
echo "3 - clears cache"
echo "0 - exit"

read Keypress

case "$Keypress" in
1) echo "installing";
    script_dir=$(dirname $0)
    cd $script_dir
    composer install
    php bin/console doctrine:database:drop --force
    php bin/console doctrine:database:create
#    php bin/console doctrine:schema:update --force
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    php bin/console server:start
;;
2) echo "import SQL file to Database";
    script_dir=$(dirname $0)
    cd $script_dir
    php bin/console doctrine:database:drop --force
    php bin/console doctrine:database:create
    php bin/console doctrine:database:import social_network.sql
;;
3) echo "clears cache";
    script_dir=$(dirname $0)
    cd $script_dir
    php bin/console cache:clear
;;
0) exit 0
;;
esac

exit 0
