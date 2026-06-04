ARG CADDY_VERSION=2.10.2

FROM caddy:${CADDY_VERSION}-builder-alpine AS caddy_builder

RUN xcaddy build

FROM caddy:${CADDY_VERSION}

WORKDIR /var/www/html

COPY --from=caddy_builder /usr/bin/caddy /usr/bin/caddy

COPY ./startup.sh /var/www/startup.sh

CMD ["/var/www/startup.sh"]
