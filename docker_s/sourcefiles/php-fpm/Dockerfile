FROM php:8.3-fpm

WORKDIR "/application"

RUN apt-get update

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install zip+icu dev libs + pgsql dev support
RUN apt-get install libzip-dev zip libicu-dev libpq-dev -y

# Устанавливаем MySQL клиент и необходимые PHP расширения
RUN apt-get install -y libmariadb-dev
RUN docker-php-ext-install pdo_mysql mysqli

# Устанавливаем редактор
RUN apt-get update && apt-get install -y nano

# Устанавливаем exif до gd!
RUN apt-get update && apt-get install -y libexif-dev

# Потом gd с jpeg + freetype
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install exif gd

# zip + intl
RUN docker-php-ext-install zip && docker-php-ext-configure intl && docker-php-ext-install intl

# pgsql
RUN docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql
