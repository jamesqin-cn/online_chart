FROM silenceper/nginx-php-fpm:php5

ADD ./Dockerfile.d/entrypoint.sh /
ADD ./Dockerfile.d/php.ini /etc/php5/php.ini
ADD ./Dockerfile.d/php-fpm.conf /etc/php5/php-fpm.conf
ADD ./Dockerfile.d/nginx.conf /etc/nginx/nginx.conf

ADD . /data/wwwroot/chart.oa.com

RUN composer install -d /data/wwwroot/chart.oa.com && mkdir -p /data/logs/ && chown -R nginx:nginx /data/wwwroot/chart.oa.com/ && chmod 755 /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
EXPOSE 9066
