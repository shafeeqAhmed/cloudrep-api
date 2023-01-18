#Aurthor: Faraz    #Backend #Development Branch 2 Aug
FROM php:8.1.7-apache
USER root
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
WORKDIR /var/www/html/
#RUN mkdir /root/Backend
COPY . /var/www/html/
ENV file_uploads=On
ENV upload_max_filesize=200M
ENV max_file_uploads=200
ENV allow_url_fopen=On
ENV post_max_size=2G
ENV memory_limit=999M
ENV max_execution_time=300
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN bash -c "sed -i 's/file_uploads =.*/file_uploads = '"$file_uploads"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/upload_max_filesize =.*/upload_max_filesize = '"$upload_max_filesize"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/max_file_uploads =.*/max_file_uploads = '"$max_file_uploads"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/allow_url_fopen =.*/allow_url_fopen = '"$allow_url_fopen"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/post_max_size =.*/post_max_size = '"$post_max_size"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/memory_limit =.*/memory_limit = '"$memory_limit"'/g' /usr/local/etc/php/php.ini"
RUN bash -c "sed -i 's/max_execution_time =.*/max_execution_time = '"$max_execution_time"'/g' /usr/local/etc/php/php.ini"
RUN printenv
RUN cat /usr/local/etc/php/php.ini
#WORKDIR /root/Backend
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
install-php-extensions  pdo_mysql zip
RUN curl -Ss https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer install
RUN mv .env.example .env
RUN cat .env
RUN echo yes | php artisan migrate
#RUN echo yes | php artisan db:seed
RUN echo yes | php artisan cache:clear
RUN echo yes | php artisan config:clear
RUN echo yes | php artisan view:clear
RUN echo yes | php artisan route:clear
RUN echo yes | php artisan optimize:clear
RUN echo yes | php artisan storage:link
RUN echo yes | php artisan storage:link
RUN echo yes | composer dumpautoload
RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
#RUN cp -r . /var/www/html/
RUN ls /var/www/html
RUN du -sh /var/www/html/
RUN chmod 777 /var/www/html -R
#EXPOSE 80
#EXPOSE 8080
WORKDIR /var/www/html/
RUN du -sh .
COPY default.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite && a2enmod rewrite headers
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
EXPOSE 80
#RUN chmod +x docker-entrypoint.sh
#ENTRYPOINT ["sh", "./docker-entrypoint.sh"]
