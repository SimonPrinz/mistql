FROM debian:11.4-slim

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="/app/bin:${PATH}" \
    PHP_INI_DIR="/etc/php/8.1"

RUN apt update \
 && apt install --no-install-recommends -y curl ca-certificates software-properties-common gnupg zip git libzip-dev build-essential \
#
# installing php repository
 && echo "deb https://packages.sury.org/php/ bullseye main" > /etc/apt/sources.list.d/php.list \
 && curl -L https://packages.sury.org/php/apt.gpg | apt-key add - \
#
# updating
 && apt update \
#
# installing php
 && apt install --no-install-recommends -y php8.1 php8.1-xml php8.1-mbstring php8.1-zip php8.1-xdebug php8.1-dev \
#
# configuring php
 && sed -i 's/memory_limit =/#memory_limit =/' $PHP_INI_DIR/cli/php.ini \
 && echo 'memory_limit = 512M' >> $PHP_INI_DIR/cli/php.ini \
#
# installing xdebug for php
 && rm -f $PHP_INI_DIR/mods-available/xdebug.ini && touch $PHP_INI_DIR/mods-available/xdebug.ini \
 && echo "zend_extension=xdebug.so" >> $PHP_INI_DIR/mods-available/xdebug.ini \
 && echo "xdebug.start_with_request=trigger" >> $PHP_INI_DIR/mods-available/xdebug.ini \
 && echo "xdebug.mode=develop,coverage,debug" >> $PHP_INI_DIR/mods-available/xdebug.ini  \
 && echo "xdebug.idekey=PHPSTORM" >> $PHP_INI_DIR/mods-available/xdebug.ini  \
 && echo "xdebug.client_host=host.docker.internal" >> $PHP_INI_DIR/mods-available/xdebug.ini \
#
# installing spx for php
 && git clone https://github.com/NoiseByNorthwest/php-spx.git php-spx && cd php-spx \
 && git checkout release/latest \
 && phpize && ./configure && make && make install \
 && cd .. && rm -rf php-spx \
 && echo 'extension=spx.so' | tee -a $PHP_INI_DIR/cli/php.ini \
 && echo 'spx.http_enabled=1' | tee -a $PHP_INI_DIR/cli/php.ini \
 && echo 'spx.http_key="dev"' | tee -a $PHP_INI_DIR/cli/php.ini \
 && echo 'spx.http_ip_whitelist="*"' | tee -a $PHP_INI_DIR/cli/php.ini \
#
# installing composer
 && curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin \
 && chmod +x /usr/local/bin/composer \
#
# cleaning up
 && apt-get autoremove -y \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app
