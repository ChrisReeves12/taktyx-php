#!/usr/bin/env bash

echo "-- Updating Apt-Get --"
sudo apt-get update

echo "-- Installing necessary dependencies --"
sudo apt-get -y install nodejs-legacy vim lsof git-core default-jre curl zlib1g-dev build-essential libssl-dev libreadline-dev libyaml-dev libsqlite3-dev sqlite3 libxml2-dev libxslt1-dev libcurl4-openssl-dev python-software-properties libffi-dev memcached redis-server

echo "-- Installing SOLR --"
sudo mkdir -p /data/additional_programs
cd /data/additional_programs
sudo wget http://apache.claz.org/lucene/solr/6.5.0/solr-6.5.0.tgz .
sudo tar -xvf solr-6.5.0.tgz
sudo solr-6.5.0/bin/install_solr_service.sh solr-6.5.0.tgz
sudo sed -ie 's/8983/8518/g' /etc/default/solr.in.sh
sudo service solr restart
sudo rm solr-6.5.0.tgz
sudo rm -r solr-6.5.0

echo "-- Install MySQL Server 5.7 --"
echo  'mysql-server-5.7 mysql-server/root_password password root' | sudo debconf-set-selections
echo  'mysql-server-5.7 mysql-server/root_password_again password root' | sudo debconf-set-selections
sudo apt-get -y install mysql-server-5.7
mysql -u root -proot -e "CREATE DATABASE taktyx_dev DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"

echo "-- Installing PHP and Composer --"
sudo apt-get -y install php7.0-cli php7.0-curl php7.0-fpm php7.0-gd php7.0-intl php7.0-json php7.0-mcrypt php7.0-mysql php7.0-readline php7.0-tidy php7.0-xml php7.0-mbstring php7.0-bcmath php7.0-bz2 php7.0-imap php7.0-zip php7.0-soap php-pear php-tideways php-apcu php-memcached php-uploadprogress php-geoip php-redis php-solr php-mongodb php7.0-pgsql php7.0-opcache php-zmq php-stomp php-imagick php-xdebug
sudo sed -ie 's/www-data/vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sudo curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

echo "-- Installing Nginx --"
sudo pkill apache2
sudo apt-get -y install nginx

echo "-- Configuring Nginx --"
sudo sed -ie 's/sendfile on/sendfile off/g' /etc/nginx/nginx.conf
sudo sed -ie 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
sudo cp /www/provision/nginx/public.conf /etc/nginx/sites-available/
sudo cp /www/provision/nginx/static.conf /etc/nginx/sites-available/
cd /etc/nginx/sites-enabled/
sudo ln -s ../sites-available/public.conf public.conf
sudo ln -s ../sites-available/static.conf static.conf
sudo rm /etc/nginx/sites-available/default
sudo rm /etc/nginx/sites-enabled/default

echo "-- Update Project Dependencies And Finish Setup --"
cd /www
composer install
php artisan migrate

sudo service nginx restart
sudo service php7.0-fpm restart

echo "-- Provisioning Complete! --"
