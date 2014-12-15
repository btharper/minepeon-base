#!/bin/bash
#
# Script to perform some common system operations
#
while :
do
clear
echo "########################################"
echo "# MinePeon Console Menu                #"
echo "########################################"
echo "# [a] Miner Screen (CTRL-A-D to exit)  #"
echo "# [s] Change console password          #"
echo "# [d] Stop Miner                       #"
echo "# [f] Start Miner                      #"
echo "# [g] Restart Miner                    #"
echo "# [z] Update MinePeon                  #"
echo "# [x] Update MinePeon Configuration    #"
echo "# [c] Update ArchLinux (MinePeon Base) #"
echo "# [r] Repair RRD (php graphic broken)  #"
echo "# [p] Repair /opt/minepeon permissions #"
echo "# [v] Reboot MinePeon                  #"
echo "# [q] Exit to shell                    #"
echo "########################################"
echo "# [Some options require your password] #"
echo "# [ Exit to shell and type logout to ] #"
echo "# [           Exit System            ] #"
echo "########################################"
echo ""
echo -n "Enter your menu choice [a-q]: "
read yourch
case $yourch in
a) /usr/bin/screen -R miner ;;
s) /usr/bin/passwd ;;
d) /usr/bin/sudo /usr/bin/systemctl stop miner ;;
f) /usr/bin/sudo /usr/bin/systemctl start miner ;;
g) /usr/bin/sudo /usr/bin/systemctl restart miner ;;
z) /opt/minepeon/bin/scripts/MinePeonUIUpdate.sh ;;
x) /opt/minepeon/bin/scripts/MinePeonConfigUpdate.sh ;;
c) /opt/minepeon/bin/scripts/ArchUpdate.sh ;;
z) /usr/bin/sudo /usr/bin/reboot ;;
r) /opt/minepeon/bin/scripts/repairRRD.sh ;;
p) /opt/minepeon/bin/scripts/fixperms.sh ;;
q) exit 0 ;;
*) echo "Please select one of the menu items";
echo "Press Enter to continue. . ." ; read ;;
esac
done

