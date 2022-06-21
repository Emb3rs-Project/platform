#!/bin/sh

cd /var/www

# php artisan migrate:fresh --seed
php artisan cache:clear
php artisan route:cache
php artisan migrate --force 
php artisan db:seed --force

/usr/bin/supervisord -c /etc/supervisord.conf