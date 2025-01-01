#!/usr/bin/env bash

rm -rf ../gen-admin
cp -a ../admin ../gen-admin

cd ../gen-admin
rm .env
cp .env.production .env
rm .env.production

composer update --optimize-autoloader --no-dev

rm -rf storage/logs/laravel.log

php artisan cache:clear

if ! [ -d node_modules/ ]; then
npm install
fi

npm run build
rm -rf node_modules

php artisan route:cache
php artisan config:cache
php artisan view:cache
php artisan event:cache


php -r '$fileName = __DIR__. "/bootstrap/cache/config.php";
    $fileContent = file_get_contents($fileName);

    $basePath = __DIR__;
    $fileContent = str_replace($basePath, "/var/www/danshin_gen/admin", $fileContent);

    file_put_contents($fileName, $fileContent);
'

cd ../

tar -cf gen-admin.tar gen-admin

scp gen-admin.tar root@5.35.93.48:/var/www
ssh root@5.35.93.48 rm -r /var/www/danshin_gen/admin
ssh root@5.35.93.48 tar -C /var/www -xvf /var/www/gen-admin.tar
ssh root@5.35.93.48 rm -rf /var/www/gen-admin.tar
ssh root@5.35.93.48 mkdir /var/www/danshin_gen
ssh root@5.35.93.48 mv /var/www/gen-admin /var/www/danshin_gen/admin
ssh root@5.35.93.48 chmod -R 777 /var/www/danshin_gen/admin/storage

rm -rf gen-admin
rm -rf gen-admin.tar

echo "files \"gen-admin\" compiled successfully"
