FROM php:8.0-cli

RUN apt-get update && apt-get install -y

RUN mkdir /app

WORKDIR /app

COPY ./server.php ./

EXPOSE 80

CMD ["php", "server.php"]
