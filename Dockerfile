FROM registry.city-srv.ru/docker/base-ubuntu:20.04

RUN apt-get update && apt-get -y upgrade \
 && apt-get install -y --no-install-recommends --no-install-suggests software-properties-common ca-certificates gettext \
 && add-apt-repository -y ppa:ondrej/php && apt-get update \
 && apt install -y nginx php8.0 php8.0-fpm php8.0-curl supervisor\
 && rm -rf /var/lib/apt/lists/*

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log \
 && mknod /var/log/app.log p && chmod 666 /var/log/app.log \
 && ln -sf /dev/stderr /var/log/php8.0-fpm.log

RUN rm -f /etc/nginx/sites-enabled/*

COPY etc/nginx.conf.tpl /tmp/nginx.conf.tpl
COPY etc/php-fpm.conf.tpl /tmp/php-fpm.conf.tpl
COPY etc/php.ini /tmp/php.ini

RUN mkdir -p /run/php && touch /run/php/php8.0-fpm.sock && touch /run/php/php8.0-fpm.pid

RUN apt-get update && apt-get -y install vim

COPY bin/entrypoint.sh /entrypoint.sh
COPY bin/stop-supervisor.sh /stop-supervisor.sh
RUN chmod 755 /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]

COPY etc/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

COPY src/ /var/www/html/

