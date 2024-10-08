# Fidelify API

## Installation

> Docker required: [how to install](https://docs.docker.com/engine/install/ubuntu/).

#### compose way

1. `docker compose up -d`
2. `docker exec -it fidelify-api bash`
3. `vendor/bin/phinx migrate`

#### standalone way

1. `docker network create -d bridge local`
2. `docker run --rm -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=fidelify --network local --name fidelify-db mysql:8.0.13`
3. `docker build -t fidelify/phpswoole .`
4. `docker run --rm -it -v c:/dev/fidelify-api:/api -p 8003:8003 --env-file .env --name fidelify-api fidelify/phpswoole`
5. `docker exec -it fidelify-api bash`
6. `vendor/bin/phinx migrate`
