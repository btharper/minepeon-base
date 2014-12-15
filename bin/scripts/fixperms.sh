#!/bin/sh

# Reset ownership and permissions for files

#Ownership
sudo chown minepeon.minepeon -R /opt/minepeon/

sudo touch /etc/php/conf.d/TZ.ini
sudo chown minepeon.minepeon /etc/php/conf.d/TZ.ini

#Permissions
sudo chmod 700 /opt/minepeon/bin/scripts/*
sudo find /opt/minepeon/etc/cron.d/ -type f -not -iname README.md -exec sudo chmod 700 {} +
