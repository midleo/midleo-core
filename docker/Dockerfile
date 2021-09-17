#openssl req -x509 -nodes -days 999 -subj "/C=CA/ST=QC/O=MIDLEO, LTD./CN=midleo.local" -addext "subjectAltName=DNS:midleo.local" -newkey rsa:2048 -keyout midleo-selfsigned.key -out midleo-selfsigned.crt
FROM nginx:latest
RUN  apt-get update && apt-get install -q -y nano openssl 

FROM php:fpm

#RUN apt-get update && apt-get install -q -y ssmtp mailutils && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev  \ 
     && docker-php-ext-configure gd --with-jpeg --with-freetype \
     && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip calendar gd \
     && rm -rf /var/lib/apt/lists/*

RUN curl -s -o /tmp/go-pear.phar http://pear.php.net/go-pear.phar && \
    echo '/usr/local/bin/php /tmp/go-pear.phar "$@"' > /usr/bin/pear && \
    chmod +x /usr/bin/pear && \
    pear install mail Net_SMTP && \
    pear install Mail_Mime
    