#!/bin/sh
#Re-Install the rrd mod for php
/usr/bin/sudo pecl uninstall rrd
/usr/bin/sudo pecl install rrd
/usr/bin/sudo reboot
