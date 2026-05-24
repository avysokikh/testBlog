#!/bin/sh
set -e

cd /var/www/html

if [ -f composer.json ] && [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist &
fi

exec php -S 0.0.0.0:8000 -t public
