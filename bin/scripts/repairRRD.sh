#!/bin/sh
#Re-Install the rrd mod for php
/usr/bin/sudo pecl uninstall rrd
/usr/bin/sudo pecl install rrd
read -p "Press enter key to continue, please, reboot for take effect"
