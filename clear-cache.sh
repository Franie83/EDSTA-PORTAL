#!/bin/bash
rm -f /var/www/html/bootstrap/cache/*.php
php artisan optimize:clear
exec /init