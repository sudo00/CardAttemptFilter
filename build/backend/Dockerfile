FROM php:8-fpm

ADD backend /app

COPY build/backend/docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT [ "/docker-entrypoint.sh" ]

