#!/bin/bash
# Setup APP script

# Install composer dependencies
composer install;

# Install yarn dependencies
cd web;
yarn;
node_modules/webpack/bin/webpack.js;
cd ../;

# Create database file
mkdir -p var/databases/;
php bin/console doctrine:database:create;

# Create database tables
### We do the following command twice because
### if only executed once, the foreign keys are not
### being applied. When executed the second time,
### it applies correctly the foreign keys.
php bin/console doctrine:schema:update --force;
php bin/console doctrine:schema:update --force;

# Populate the database with default, minimum data needed for running the application
php bin/console db:populate;

# Run the server on port 8000
php bin/console server:run 0.0.0.0:8000;
