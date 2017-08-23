install using :

composer install
npm install


adjust laravel passpor service provider in file :

storage/framework/packages.php

'Laravel\\Passport\\PassportServiceProvider',

as it is already added in our list so.

and you are good to go.

then

then run migration.  : php artisan migrate
then isntall passport  : php artisan passport:install

then :  update API client details in controllers/LoginApi.php.

then setup :
http://fashionhub.local.com/admin/user/users


