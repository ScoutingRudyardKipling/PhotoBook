#!/bin/bash


branch=$1
directory=$2 #/var/www/$repoFolder/

# Notify
echo "---"
echo "Deploy script has been started."
echo "Any unsaved data in the app directory will be overwritten."
echo "If you DO NOT want this, press CTRL + C within 5 seconds."
echo "---"
echo "5"
sleep 1
echo "4"
sleep 1
echo "3"
sleep 1
echo "2"
sleep 1
echo "1"
sleep 1
echo "---"
echo "Deploy script started, please keep this window open during the process."
echo "---"
echo ""
# End notify
# Go to repo directory
cd $directory
echo "Changed directory..."
echo ""
php artisan down
echo "Set laravel in maintenance mode"
echo ""
# Fetch
git fetch
echo "Data fetched..."
echo ""
# Checkout git
git checkout $branch
echo "Checkout $branch"
echo ""
git pull
echo "Pulled latest changes from Git"
echo ""
# Update version number
echo "Updating version number"
git rev-parse HEAD > .version
echo ""
# Install composer dep.
php composer.phar install --no-dev
echo "Composer has been updated"
echo ""
# discover packages
php artisan package:discover
echo "Packages discovered"
echo ""
# migrate
echo "Starting to migrate master database"
php artisan migrate --force
echo "Migration of master-database was successful"
echo ""
sleep 1
# Clear application and view cache
php artisan config:clear
php artisan permission:cache-reset
php artisan cache:clear
php artisan view:clear
echo "Cache cleared..."
echo ""
# NPM production --> Asset compiling
npm ci
npm run production
echo "Assets successfully built in production mode."
echo ""
sleep 1
#up the application
echo "Set laravel in production mode"
echo ""
php artisan up
echo ""
# Closing
echo "---"
echo "Deploy script successfully completed."
echo "---"
