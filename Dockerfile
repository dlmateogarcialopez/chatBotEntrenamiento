FROM php:7.3-apache

USER root

RUN mkdir -p /var/www/html/chatbotchec

WORKDIR /var/www/html/chatbotchec 

COPY . /var/www/html/chatbotchec
#COPY ./chatWeb /var/www/html/chatWeb

#required libraries installed 
RUN apt-get update && apt-get install -y openssl libssl-dev libcurl4-openssl-dev libxml2-dev libc-client-dev libkrb5-dev zip unzip

#COMPOSER CONFIG
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.0.7

#MONGODB DRIVER CONFIG
RUN pecl install mongodb
RUN docker-php-ext-enable /usr/local/lib/php/extensions/no-debug-non-zts-20180731/mongodb.so

#IMAP CONFIG
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-imap.ini
RUN echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/imap.so" >> /usr/local/etc/php/conf.d/docker-php-ext-imap.ini

#SOAP CONFIG
RUN docker-php-ext-install -j$(nproc) soap
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-soap.ini
RUN echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/soap.so" >> /usr/local/etc/php/conf.d/docker-php-ext-soap.ini

#DOTENV
RUN composer require vlucas/phpdotenv 

#INI.PHP CONFIG
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

#GENERAL CONFIGURATION
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*


#EXPOSE 80