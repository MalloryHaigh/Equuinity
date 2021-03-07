FROM nginx:alpine

COPY ./docker/nginx/nginx.nginx /etc/nginx/nginx.conf
COPY ./docker/nginx/default.nginx /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

COPY ./public ./public