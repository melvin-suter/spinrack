#!/bin/bash

# Get environment
source /etc/container.env

# For Debuging: /bin/echo "[$(date)] Running cron (*/5)" # Or (*/1), whatever cron it is

# Get in the right place
cd /var/www/html 

# Running your script here

/usr/local/bin/php artisan app:process-jobs