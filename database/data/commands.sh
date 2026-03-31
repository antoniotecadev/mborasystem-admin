#!/bin/bash

# This file contains commands to import the sample data into the database.
# You can run these commands in your terminal to populate the database with the sample data.
php artisan geo:import-angola --path=database/data/angola_geo.sample.json --truncate
php artisan geo:import-angola --path=database/data/angola_geo.sample.csv --truncate