
# Scouting Rudyard Kipling PhotoBook

This is the online photobook built for the scoutsgroup Scouting Rudyard Kipling.
It features:

* Sol protected logins (Because we need to be AVG proof :see_no_evil: )
* Uploading images
* Administrator accounts
* Favorites

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment chapter for notes on how to deploy the project on a 
live 
system.

### Prerequisites

What things you need to install the software and how to install them
 * [Composer](https://getcomposer.org/)
 * php 7.1.3 or higher (preferred latest)
 * A (local) MySQL database
 * [Node.js](https://nodejs.org/en/download/)
 * NPM (installed with Node.js)
 * Common sense :wink:

### Installing (development environment)

A step by step series of examples that tell you how to get a development env running

create a local .env
```
cp .env.example .env
```
Now setup the .env  file for your environment

Minimal setup is setting up your database connection
```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ews
DB_USERNAME=ews
DB_PASSWORD=ews
```

Install composer dependencies
```bash
composer install
```
Generate a local key for laravel (stored in .env)
```bash
php artisan key:generate
```
link the storage folder
```bash
php artisan storage:link
```
Migrate the database (from scratch) to the latest version
```bash
php artisan migrate
```
To see a list of available artisan commands
```bash
php artisan list
```
As you will use artisan a lot (and we as programmers are lazy) make an alias in your .bash_profile
```bash
alias art='php artisan'
``` 
Do you want to start out with the project with some pre filled fake data
```bash
php artisan db:seed
``` 
We also need to install npm with its packages
```bash
npm install
```
And run npm to let it generate a nice and clean looking website for you
```bash
npm run dev
```

## Running the tests

Run all tests

```bash
composer test
```
The above example runs phpunit, phpcs, phpmd, and larastan. Info found in the rest of this paragraph.
### phpunit - all defined tests

This command will run all php unit tests found in the test folder. this includes Unit as well as Feature tests.
```bash
./vendor/bin/phpunit
```

### Static code analysers and coding style tests
For running all analysers at once run:
```bash
composer lint
```
#### phpcs -  PHP Code Sniffer
PHP_CodeSniffer tokenizes PHP, JavaScript and CSS files and detects violations of a defined set of coding standards.

Configuration of CodeSniffer is found in phpcs.xml

[PHPCS repo and guide](https://github.com/squizlabs/PHP_CodeSniffer)
```bash
./vendor/bin/phpcs
```
#### phpmd - PHP Mess Detector
What PHPMD does is: It takes a given PHP source code base and look for several potential problems within that source. These problems can be things like:
* Possible bugs
* Suboptimal code
* Overcomplicated expressions
* Unused parameters, methods, properties

Configuration of MessDetector is found in phpmd.xml

[PHPMD repo and guide](https://github.com/phpmd/phpmd)
```bash
#executable path   
#                  tested folder or file
#                      output format
#                           configuration
./vendor/bin/phpmd app text phpmd.xml
```
#### Larastan
Discover bugs in your code without running it - phpstan wrapper for Laravel.

Configuration of Larastan is found in phpstan.neon

[Larastan repo and guide](https://github.com/nunomaduro/larastan)
```bash
php artisan code:analyse --level=max
```

## Deployment

If the application is already installed for the first time, skip the First time deployment paragraph and go straight to Updating.

### First time deployment

We will go a little bit faster here, if you would like some more explanation, go to the above paragraph Installing, or visit the laravel documentation page

download the repo on the server (via git or filetransfer) and make sure you are in the folder of this repo.

create a local .env
```bash
cp .env.example .env
```
Now setup the .env file for your environment. At least setup the next fields
```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your.url

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-user
DB_PASSWORD=your-password

```
now run the next commands to setup the server
```bash
composer install
php artisan package:discover
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan cache:clear
php artisan config:cache
npm ci
npm run production
php artisan up

```
Install a cronjob to call the laravel kernel which dispatches all jobs internally, yes it has to run every minute.
Laravel will determine for itself when to run the jobs.
```
crontab -e
# add the next line to file
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### webserver

Make sure you have the base setup the apache or nginx config correctly and routed to the public folder.

* make sure it will route to the ```/public``` folder in the repo (NOT TO THE BASE FOLDER)

##### apache

Laravel includes a ```public/.htaccess``` file that is used to provide URLs without the ```index.php``` front controller in the path. 
Before serving Laravel with Apache, be sure to enable the ```mod_rewrite``` module so the ```.htaccess``` file will be honored by the server.

If the ```.htaccess``` file that ships with Laravel does not work with your Apache installation, try this alternative:
```apacheconfig
Options +FollowSymLinks -Indexes
RewriteEngine On

RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

##### nginx

If you are using Nginx, the following directive in your site configuration will direct all requests to the ```index.php``` front controller:
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### Updating

run command
```bash
php artisan down
```

Upload your new repository to the server.

Make sure you do not edit or overwrite:
* ```.env``` unless edits are specified for that particular update
* ```vendor/```
* ```storage/```
* ```node_modules/```

run commands
```bash

composer install
php artisan migrate

php artisan cache:clear
php artisan config:cache
npm ci
npm run production

php artisan up
```

## Built With
* [Laravel](https://laravel.com/docs/5.7) - The web framework used (with its default included packages), read more in the About Laravel section.
* [Laravel IDE helper](https://github.com/barryvdh/laravel-ide-helper) - Generates an ide helper file for better hinting. Also generates model blocks on demand.
* [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Code Sniffer tokenizes PHP, JavaScript and CSS files and detects violations of a defined set of coding standards.
* [Larastan](https://github.com/nunomaduro/larastan) - Discover bugs in your code without running it - phpstan wrapper for Laravel.
* [PHPMD](https://github.com/phpmd/phpmd) - Takes a given PHP code base and looks for several potential problems within that source.
* [Spatie Laravel Medialibary](https://docs.spatie.be/laravel-medialibrary/v7/introduction/) - The laravel media libary used
* [Spatie Laravel Permission](https://docs.spatie.be/laravel-permission/v2/introduction/) - The laravel permission libary
### About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1400 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Security Vulnerabilities
* [CVE details Laravel](https://www.cvedetails.com/product/38139/?q=Laravel)

## Authors

* **Friso Modderman** - *Initial work* - [adminfriso](https://github.com/adminfriso)
