FROM silenceper/nginx-php-fpm:php7

ADD ./Dockerfile.d/entrypoint.sh /
ADD ./Dockerfile.d/php.ini /etc/php7/conf.d/php.ini
ADD ./Dockerfile.d/php-fpm.conf /etc/php7/php-fpm.d/www.conf
ADD ./Dockerfile.d/nginx.conf /etc/nginx/nginx.conf
ADD . /data/wwwroot/chart.oa.com

RUN composer install -d /data/wwwroot/chart.oa.com && mkdir -p /data/logs/ && chown -R nginx:nginx /data/wwwroot/chart.oa.com/ && chmod 755 /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
EXPOSE 9066
