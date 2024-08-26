php artisan optimize:clear

php artisan storage:unlink
php artisan storage:link

php artisan db:wipe
php artisan migrate:fresh --seed
php artisan shield:super-admin --user=1
php artisan shield:generate --all

composer dump-autoload
