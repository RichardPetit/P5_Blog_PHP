FROM devilbox/php-fpm:7.4-prod

RUN apt-get update -y && apt-get install -y coreutils make curl wget bash

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

WORKDIR /var/www/app
EXPOSE 9000
