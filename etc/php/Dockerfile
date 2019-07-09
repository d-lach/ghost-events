FROM php:7.1-fpm

MAINTAINER Lukasz Malicki <lukasz.malicki@polcode.net> & Vladyslav Gaysyuk <vladyslav.gaysyuk@polcode.net>

RUN apt-get update > /dev/null && apt-get install -y \
    git \
    unzip \
    libjpeg-dev \
    libxpm-dev \
    libwebp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libicu-dev \
    jpegoptim \
    g++ \
    libxrender1 \
    libfontconfig \
    nano > /dev/null \
    && docker-php-ext-install -j$(nproc) iconv mcrypt > /dev/null \
    && docker-php-ext-install intl > /dev/null \
    && docker-php-ext-install zip > /dev/null


#
#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------
#
# Optional Software's will only be installed if you set them to `true`
# in the `docker-compose.yml` before the build.
# Example:
#   - INSTALL_ZIP_ARCHIVE=true
#

ARG INSTALL_NODE=false
RUN if [ ${INSTALL_NODE} = true ]; then \
    # Install NodeJS using NVM
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.5/install.sh | bash > /dev/null && \
    export NVM_DIR="$HOME/.nvm" > /dev/null && \
    [ -s "$NVM_DIR/nvm.sh" ] > /dev/null && . "$NVM_DIR/nvm.sh" > /dev/null && \
    nvm install 7.2 > /dev/null && \
    nvm use node > /dev/null \
;fi

ARG INSTALL_GULP=false
RUN if [ ${INSTALL_GULP} = true ]; then \
    # Install globaly gulp
    npm install -g gulp > /dev/null \
;fi

ARG INSTALL_BOWER=false
RUN if [ ${INSTALL_BOWER} = true ]; then \
    # Install globaly bower
    npm install -g bower > /dev/null \
;fi


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer > /dev/null


# # Set timezone
#@deprecated, timezone should be set into php.ini config
# RUN rm /etc/localtime \
#   && ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime \
#   && "date"


ARG INSTALL_MYSQL=false
RUN if [ ${INSTALL_MYSQL} = true ]; then \
    # Install MySQL PDO
    docker-php-ext-install pdo pdo_mysql > /dev/null \
;fi

ARG INSTALL_POSTGRESQL=false
RUN if [ ${ISNTALL_POSTGRESQL} = true ]; then \
    # Install PostgreSQL PDO
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql > /dev/null && \
    docker-php-ext-install pgsql pdo_pgsql > /dev/null \
;fi

ARG INSTALL_GD=false
RUN if [ ${INSTALL_GD} = true ]; then \
    # Install GD library
    docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-xpm-dir=/usr/include/ \
        --with-webp-dir=/usr/include/ > /dev/null && \
    docker-php-ext-install gd > /dev/null \
;fi


ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    # Install XDebug extention PDO
    pecl install xdebug > /dev/null && \
    docker-php-ext-enable xdebug > /dev/null \
;fi

ARG ADD_ALIASES=false
RUN if [ ${ADD_ALIASES} = true ]; then \
    # Install GD library
    echo 'alias sf="php app/console"' >> ~/.bashrc  && \
    echo 'alias sf3="php bin/console"' >> ~/.bashrc && \
    echo 'alias lv="php artisan"' >> ~/.bashrc \
;fi

WORKDIR /var/www/html
