FROM php:8.3.11-fpm-alpine3.19

USER 'root'

RUN apk --update --no-cache add \
        linux-headers \
        shadow \
        autoconf \
        zip \
        php83 \
        php83-ctype \
        php83-curl \
        php83-session \
        php83-zip \
        php83-mbstring \
        php83-pcntl \
        php83-zlib \
        php83-xml

RUN apk --update --no-cache add libpq-dev \
        && docker-php-ext-install pdo pdo_pgsql \
    && apk --update --no-cache add --virtual .phpize_deps $PHPIZE_DEPS \
    && pecl install -f xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .phpize_deps

### Composer
RUN curl https://getcomposer.org/composer-2.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

#1000
ARG USER_ID
#1000
ARG GROUP_ID
#www-data
ARG USERNAME

## Creating user
RUN if getent passwd ${USERNAME} ; then userdel -f ${USERNAME}; fi
RUN if getent group ${USERNAME} ; then groupdel -f ${USERNAME}; fi
RUN if getent group ${GROUP_ID} ; then groupdel -f $(getent group ${GROUP_ID} | cut -d: -f1); fi
RUN groupadd -g ${GROUP_ID} ${USERNAME}
RUN useradd -l -u ${USER_ID} -g ${USERNAME} ${USERNAME}
RUN install -d -m 0755 -o ${USERNAME} -g ${USERNAME} /home/${USERNAME}

USER ${USERNAME}

WORKDIR /var/www/app
