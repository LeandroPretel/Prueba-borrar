#!/bin/bash

cd /home/ubuntu/app/backend
#composer install
sudo chmod 777 -R storage
sudo chmod 777 -R bootsrap/cache

cp /home/ubuntu/app/backend/scripts/nginx /etc/nginx/sites-available/api.test.pruebas.beebit.es
sudo ln -s /etc/nginx/sites-available/api.test.pruebas.beebit.es /etc/nginx/sites-enabled/api.test.pruebas.beebit.es
sudo service nginx restart

sudo -i -u postgres psql -c "CREATE DATABASE pruebas;"
sudo -i -u postgres psql -c "create user pruebas with encrypted password 'pruebas';"
sudo -i -u postgres psql -c "alter role pruebas superuser;"
sudo -i -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE 'pruebas' TO pruebas;"

cd /home/ubuntu/backend
php artisan migrate
php artisan optimize

# sudo certbot --nginx --redirect -d api.test.pruebas.beebit.es