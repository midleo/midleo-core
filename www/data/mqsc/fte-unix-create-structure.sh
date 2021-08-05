#!/bin/bash

yum install make
yum install librabbitmq.x86_64
yum install librabbitmq-devel.x86_64
pecl install amqp
# add "extension=amqp.so" to php.ini
service php-fpm restart
service httpd restart

#root
mkdir -p /var/midleo
chown -R mqm:mqm /var/midleo/
#mqm - upload iot.sh to /var/midleo/
#mqm - upload q to /var/midleo/
chmod +x /var/midleo/iot.sh
chmod +x /var/midleo/q

