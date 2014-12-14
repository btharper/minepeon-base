#!/bin/sh
#Re-Install the rrd mod for php
/usr/bin/sudo pecl uninstall rrd
/usr/bin/sudo pecl install rrd
/usr/bin/sudo chmod 555 -R /opt/minepeon/etc/cron.d/5min/RECORDHashrate
read -p "Press enter key to continue, please, reboot for take effect"
