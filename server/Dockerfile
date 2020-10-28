FROM trafex/alpine-nginx-php7:1.8.0
USER root

# ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

# RUN apk --update add ca-certificates && \
#     echo "https://dl.bintray.com/php-alpine/v3.11/php-7.4" >> /etc/apk/repositories

# install php and some extensions
RUN apk update && apk upgrade

RUN apk add --no-cache openssl bash nodejs npm postgresql-dev git openssh-client zlib-dev libzip-dev libpng-dev sshpass supervisor

# RUN ln -s /usr/bin/php7 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG SSH_PRIVATE_KEY="./privateKey.key"
ARG SSH_PASS_KEY="secret"
ARG SSH_PASSWORD="secret"
ARG SSH_PEM="secret"
ARG REPO="git@gitlab.com:beebit/devops.git"
ARG DOMAINS="localhost"
ARG SERVER_ROOT="/var/www/html/backend/public"
ARG ARG_DATABASE="example"
ARG ARG_USERNAME="root"
ARG ARG_PASSWORD="password"
ARG ARG_DBHOST="dbhost"
ARG ARG_AWS_ACCESS_KEY_ID="sample"
ARG ARG_AWS_SECRET_ACCESS_KEY="sample"
ARG ARG_AWS_DEFAULT_REGION="eu-west-1"
ARG ARG_AWS_BUCKET="sample"

ENV DATABASE ${ARG_DATABASE}
ENV USERNAME ${ARG_USERNAME}
ENV PASSWORD ${ARG_PASSWORD}
ENV DBHOST ${ARG_DBHOST}
ENV AWS_ACCESS_KEY_ID ${ARG_AWS_ACCESS_KEY_ID}
ENV AWS_SECRET_ACCESS_KEY ${ARG_AWS_SECRET_ACCESS_KEY}
ENV AWS_DEFAULT_REGION ${ARG_AWS_DEFAULT_REGION}
ENV AWS_BUCKET ${ARG_AWS_BUCKET}

RUN mkdir -p /opt/keys
ADD $SSH_PRIVATE_KEY /opt/keys/private.key

RUN chmod 600 /opt/keys
RUN mkdir /root/.ssh/

RUN touch /root/.ssh/known_hosts
RUN ssh-keyscan github.com >> /root/.ssh/known_hosts

RUN echo "exec cat" > ap-cat.sh
RUN export DISPLAY=1
RUN chmod +x ap-cat.sh
RUN chmod 400 /opt/keys/private.key
ADD server.conf /etc/nginx/conf.d/server.conf
ADD nginx.conf /etc/nginx/nginx.conf
ADD php-settings.ini /etc/php7/conf.d/settings.ini

RUN eval $(ssh-agent -s) && echo $SSH_PASS_KEY | SSH_ASKPASS=./ap-cat.sh ssh-add /opt/keys/private.key && cd /var/www/html && git clone ${REPO} backend
RUN cd /var/www/html/backend && git checkout staging
RUN apk add php7-pdo php7-zip php7-bcmath php7-pdo_pgsql php7-tokenizer php7-fileinfo php7-xmlwriter php7-simplexml php7-iconv php7-gmp
RUN mv /etc/php7/conf.d/custom.ini /etc/php7/conf.d/custom.ini.bak
ADD php.ini /etc/php7/conf.d/custom.ini

RUN mv /etc/php7/conf.d/settings.ini /etc/php7/conf.d/settings.ini.bak
ADD settings.ini /etc/php7/conf.d/settings.ini
RUN eval $(ssh-agent -s) && echo $SSH_PASS_KEY | SSH_ASKPASS=./ap-cat.sh ssh-add /opt/keys/private.key && cd /var/www/html/backend && composer install --ignore-platform-reqs
RUN mv /etc/php7/conf.d/settings.ini.bak /etc/php7/conf.d/settings.ini

RUN cd /var/www/html/backend
RUN chmod -R 755 .
RUN chmod -R 777 /var/www/html/backend/storage

#ADD .env /var/www/html/backend
RUN sed 's/DOMAINS/'"${DOMAINS}"'/g' /etc/nginx/conf.d/server.conf | tee /etc/nginx/conf.d/server.conf
RUN sed 's#SERVER_ROOT#'"${SERVER_ROOT}"'#g' /etc/nginx/conf.d/server.conf | tee /etc/nginx/conf.d/server.conf

# RUN cd /var/www/html/backend/ && php artisan optimize && php artisan queue:work --queue=anfix
EXPOSE 8080

USER root