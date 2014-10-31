minepeon-base (VoxDemonix Edition)
=============

Add News Options: 

-> Webcam Dispay On/Off on index's page (via settings.php)

-> Choose Skin (settings.php)

-> Save option in xml file can be read by a human (/opt/minepeon/http/xml/settingsSkin.xml)

-> Display BTC/Euro/Dollars comparaison to menu (the function is in skin.php and use in menu.php and index.php)

-> Add option for can support other contributors


#Official url: 

  http://minepeon.com/forums/viewtopic.php?f=19&t=1756
  
  https://hnokrjnlzme4v5yv.onion/forum/viewtopic.php?f=13&t=6&p=7
  
#How to install:

  └─ $ ▶ rm /opt/minepeon/bin/bitstreams/README
  
  └─ $ ▶ cd /opt/minepeon
  
  └─ $ ▶ git rm --cached -r .
  
  └─ $ ▶ git reset --hard
  
  └─ $ ▶ git pull https://github.com/voxdemonix/minepeon-base.git



#Depencance:
If you want can use the webcam you need install "motion":

└─ $ ▶ sudo pacman -S motion

Creat the folder

└─ $ ▶ mkdir ~/motion

the following line creat the config file

└─ $ ▶ nano ~/motion/motion.config

And drop the following line in the motion.config file (one command peer line)

videodevice /dev/video0

input 8

start_motion_daemon=no

webcam_localhost off

quiet on

post_capture 0

output_all off

control_localhost off

output_normal off

webcam_port 6881

width 640

height 480

webcam_maxrate 25



For lunch the webcam open a screen

└─ $ ▶ screen -R motion

Lunch the program

└─ $ ▶ sudo motion -c ~/motion/motion.config


#How to securise SSL (PODDLE):

    sudo nano /etc/httpd/conf/extra/httpd-ssl.conf

Write "SSLProtocol all -SSLv3" just after "SSLEngine on" and reboot.
(source : https://hnokrjnlzme4v5yv.onion/joomla/index.php/fr/tout-les-articles-fr/7-mitm-sslv3-faille-poddle

#MinePeonUser:

To make the MinePeon user;-

groupadd -g 500 minepeon

useradd -m -u 500 -g 500 -d /home/minepeon -p peon minepeon

user: minepeon (UID 500)

group: minepeon (GID 500)

minepeon-base

PHP branch


How to make a Plugin to Minepeon
=

Take a look here at the wiki:
https://github.com/MineForeman/minepeon-base/wiki/How-to-make-a-plugin-using-the-api
