## Setup

    docker run --rm -ti --volume $PWD/app:/app composer install

    docker run --rm -ti --volume $PWD/web:/app composer install

    docker-compose build

    docker-compose up -d web mysql redis

    docker-compose run --rm app php setup.php

## Run

    docker-compose up app
