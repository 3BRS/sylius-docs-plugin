FROM node:20-alpine AS node

FROM ghcr.io/sylius/sylius-php:8.3-xdebug-alpine

RUN apk add --no-cache \
    bash \
    make

COPY --from=node /usr/lib /usr/lib
COPY --from=node /usr/local/share /usr/local/share
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin
COPY --from=node /opt /opt

##################
# Chromium
##################
RUN apk update && apk add --no-cache \
    wget \
    curl \
    gnupg

RUN apk add --no-cache chromium

##################
# CLEANUP
##################
RUN apk del

CMD [ "php-fpm" ]

ENTRYPOINT [ "docker-php-entrypoint" ]
