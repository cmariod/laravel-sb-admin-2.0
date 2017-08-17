# Laravel SB Admin

##Setup

#### Instalation
1. Clone this repository
2. Install [Composer](https://getcomposer.org/doc/00-intro.md)
3. run `composer install` from the project root where composer.json located, grab a popcorn and wait
4. run `npm install` from the project root, grab an ice cream and wait
5. run `bower install` from the project root, grab a coffee and wait
6. Create .env based from .env.sample to match the current installed environment
7. setup database schema by executing `php artisan migrate` & `php artisan db:seed` or `php artisan migrate:refresh --seed` to completely re-build database

#### Cron Setup
1. Create sync/temp & sync/archive folder if .env['APP_BUILD'] is master. Make sure folder is writable by apache user.
2. Setup crontab and add in lines to `* * * * * php /var/www/cagxmas/artisan schedule:run >> /dev/null 2>&1`

#### SB Admin Templates
visit /public/sbadmin

##Artisan Helper

## Deployment & Maintenance

### Archiving for Production Deployment
`tar -czvf ~/Desktop/laravel-archive.tar.gz --disable-copyfile --exclude .git --exclude .DS_Store --exclude readme.md --exclude private --exclude ".git*" --exclude .env --exclude ".env*" --exclude "storage/logs/*"  --exclude "storage/app/*" --exclude "bower_components/*" --exclude "node_modules/*" --exclude "tests/*" ./`

#### Deployment Steps
1. Upload the archive from above to the designated S3 bucket launchconfig.
2. From load balancer, spin ups new instance that will automatically install this from saved AMI.
or
3. Execute server snippets from S3 bucket launchconfig/server-snippets/patch.sh