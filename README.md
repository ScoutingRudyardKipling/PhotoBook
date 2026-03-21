
# Scouting Rudyard Kipling PhotoBook

This is the online photobook, initially built for scoutsgroup Scouting Rudyard Kipling, but now also open for other scoutsgroups or organisations.
It features:

* Watching your own pictures online and on your own server
* Organize your pictures in albums
* Uploading images via the web portal
* User management, with different roles and permissions (admin, content creator & subscribers)
* Scouts online (sol) protected logins (Because we need to be AVG proof :see_no_evil: )

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment chapter for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them
 * [Composer](https://getcomposer.org/)
 * PHP 8.3 or higher
 * A (local) SQL database (MariaDB 10.2 or higher)
 * [Node.js 18+](https://nodejs.org/en/download/) and NPM — required for local development only (not needed on the server)
 * Common sense :wink:

### Installing (development environment)

A step by step series of examples that tell you how to get a development env running

create a local .env
```
cp .env.example .env
```
Now setup the .env file for your environment

Minimal setup is setting up your database connection
```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photobook
DB_USERNAME=photobook
DB_PASSWORD=photobook
```

Install composer dependencies
```bash
composer install
```
Generate a local key for laravel (stored in .env)
```bash
php artisan key:generate
```
Link the storage folder
```bash
php artisan storage:link
```
Migrate the database (from scratch) to the latest version
```bash
php artisan migrate
```
Seed the database with the bare essential data (like basic permissions and roles)
```bash
php artisan db:seed
```
To see a list of available artisan commands
```bash
php artisan list
```
As you will use artisan a lot (and we as programmers are lazy) make an alias in your .bash_profile
```bash
alias art='php artisan'
```
Install npm packages and start the Vite dev server (with HMR)
```bash
npm install
npm run dev
```
To build production assets locally (committed to the repo, not required on the server)
```bash
npm run build
```

## Running the tests

Run all tests

```bash
composer test
```
The above example runs phpunit, phpcs, phpmd, and larastan. Info found in the rest of this paragraph.

### phpunit - all defined tests

This command will run all php unit tests found in the test folder. This includes Unit as well as Feature tests.
```bash
./vendor/bin/phpunit
```

### Static code analysers and coding style tests
For running all analysers at once run:
```bash
composer lint
```
#### phpcs - PHP Code Sniffer
PHP_CodeSniffer tokenizes PHP files and detects violations of a defined set of coding standards.

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
./vendor/bin/phpmd app text phpmd.xml
```
#### Larastan
Discover bugs in your code without running it — phpstan wrapper for Laravel.

Configuration of Larastan is found in phpstan.neon

[Larastan repo and guide](https://github.com/larastan/larastan)
```bash
./vendor/bin/phpstan analyse --memory-limit=1G
```

## Deployment

If the application is already installed for the first time, skip the First time deployment paragraph and go straight to Updating.

### First time deployment

We will go a little bit faster here, if you would like some more explanation, go to the above paragraph Installing, or visit the laravel documentation page.

Download the repo on the server (via git or filetransfer) and make sure you are in the folder of this repo.

Create a local .env
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
Now run the next commands to setup the server
```bash
composer install --no-dev
php artisan package:discover
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan cache:clear
php artisan config:cache
php artisan up
```

> **Note:** Node.js is not required on the server. Frontend assets are pre-built locally and committed to the repository under `public/build/`.

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

The easiest way to update is via the included `update.sh` script:
```bash
bash update.sh <branch> <directory>
```

Or manually:
```bash
php artisan down

git pull

composer install --no-dev
php artisan package:discover
php artisan migrate --force
php artisan config:clear
php artisan permission:cache-reset
php artisan cache:clear
php artisan view:clear

php artisan up
```

> **Note:** No `npm` steps are needed on the server. Run `npm run build` locally and commit `public/build/` before deploying.

## Built With
* [Laravel 12](https://laravel.com/docs/12.x) - The web framework used
* [Vite](https://vitejs.dev/) - Frontend asset bundler
* [Bootstrap 5](https://getbootstrap.com/) - CSS framework
* [GLightbox](https://biati-digital.github.io/glightbox/) - Image lightbox
* [Uppy](https://uppy.io/) - File upload widget
* [Laravel IDE helper](https://github.com/barryvdh/laravel-ide-helper) - Generates an ide helper file for better hinting
* [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Code Sniffer tokenizes PHP files and detects violations of a defined set of coding standards
* [Larastan](https://github.com/larastan/larastan) - Discover bugs in your code without running it — phpstan wrapper for Laravel
* [PHPMD](https://github.com/phpmd/phpmd) - Takes a given PHP code base and looks for several potential problems within that source
* [Spatie Laravel Medialibrary](https://spatie.be/docs/laravel-medialibrary) - Media library for Laravel
* [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Role and permission handling for Laravel

## Security Vulnerabilities
* [CVE details Laravel](https://www.cvedetails.com/product/38139/?q=Laravel)

## Authors

* **Friso Modderman** - *Initial work* - [adminfriso](https://github.com/adminfriso)
