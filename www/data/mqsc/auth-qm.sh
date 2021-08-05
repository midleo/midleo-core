#!/bin/bash

#QMANAGER - your qmanager which you want to access
#GROUPNAME - the group which you have configured in the channel - added in App servers section
setmqaut -m QMANAGER -t qmgr -g GROUPNAME +connect +inq +dsp +crt +chg
setmqaut -m QMANAGER -n SYSTEM.ADMIN.COMMAND.QUEUE -t queue -g GROUPNAME +put +inq +chg
setmqaut -m QMANAGER -n SYSTEM.DEFAULT.MODEL.QUEUE -t queue -g GROUPNAME +get +dsp +chg
setmqaut -m QMANAGER -n SYSTEM.DEFAULT.LOCAL.QUEUE -t queue -g GROUPNAME +dsp +chg +put
setmqaut -m QMANAGER -n SYSTEM.DEFAULT.ALIAS.QUEUE -t queue -g GROUPNAME +dsp +chg
setmqaut -m QMANAGER -n SYSTEM.DEFAULT.REMOTE.QUEUE -t queue -g GROUPNAME +dsp +chg +put

echo 'refresh security(*)' | runmqsc QMANAGER