FROM wordpress:latest

# 依存パッケージ追加
RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends unzip zlib1g-dev libzip-dev \
    && docker-php-ext-install zip mysqli \
    && docker-php-ext-configure zip mysqli \
    ; \
    apt-get autoremove; \
    apt-get clean; \
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp \
    && wp --info \
    ;
COPY --from=composer /usr/bin/composer /usr/bin/composer

# 設定ファイルをコピー
COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf
