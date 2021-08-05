#!/bin/bash

INSTALLDIR=/var/midleo/
SYSTEMQ=SYSTEM.MIDLEO.IOT.QUEUE
SYSTEMQMGR=VMQFT01

${INSTALLDIR}q -m ${SYSTEMQMGR} -I ${SYSTEMQ} -F ${INSTALLDIR}iot.xml

AGTNAME=`cat ${INSTALLDIR}iot.xml | grep -oPm1 "(?<=<agent>)[^<]+"`
AGTQMGR=`cat ${INSTALLDIR}iot.xml | grep -Pio 'sourceAgent.*QMgr="\K[^"]*'`

echo /opt/mqm/bin/fteCreateMonitor -f -ma ${AGTNAME} -mm ${AGTQMGR} -ix ${INSTALLDIR}iot.xml > ${INSTALLDIR}iot.reply.log
/opt/mqm/bin/fteCreateMonitor -f -ma ${AGTNAME} -mm ${AGTQMGR} -ix ${INSTALLDIR}iot.xml >> ${INSTALLDIR}iot.reply.log
${INSTALLDIR}q -m ${SYSTEMQMGR} -o ${SYSTEMQ}.REPLY -F ${INSTALLDIR}iot.reply.log

rm ${INSTALLDIR}iot.xml
rm ${INSTALLDIR}iot.reply.log
