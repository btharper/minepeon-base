minepeon-base (VoxDemonix Edition)
=============

Add News Options: 

-> Webcam Dispay On/Off on index's page (via settings.php)

-> Choose Skin (settings.php)

-> Save option in xml file can be read by a human (/opt/minepeon/http/xml/settingsSkin.xml)

-> Display BTC/Euro/Dollars comparaison to menu (the function is in skin.php and use in menu.php and index.php)


For fix the graphic's bug:
http://minepeon.com/forums/viewtopic.php?f=19&t=1756&p=8427#p8427


#Depencance:
If you want can use the webcam you need install "motion":

└─ $ ▶ sudo pacman -S motion

Creat the folder
└─ $ ▶ mkdir ~/motion

the following line creat the config file

└─ $ ▶ nano ~/motion/motion.config
And drop the following line in the motion.config file

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
