FROM alpine:3.10

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk add --update --no-cache nginx bash

COPY nginx.conf /etc/nginx/
COPY app.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
