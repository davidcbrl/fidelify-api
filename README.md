# Fidelify API

## Installation

> Docker required: [how to install](https://docs.docker.com/engine/install/ubuntu/).
> Replace <project-dir> with your project directory path.

#### compose way

1. `cd <project-dir>`
2. `docker compose up -d`
3. `docker exec -it fidelify-api bash`
4. `vendor/bin/phinx migrate`
5. `exit`

#### standalone way

1. `cd <project-dir>`
2. `docker network create -d bridge local`
3. `docker run --rm -v <project-dir>/.data/mysql:/var/lib/mysql -p 3306:3306 --env-file .env --network local --name fidelify-db mysql:8.0.13`
4. `docker build -t fidelify/phpswoole .`
5. `docker run --rm -it -v <project-dir>:/api -p 8003:8003 --env-file .env --network local --name fidelify-api fidelify/phpswoole`
6. `docker exec -it fidelify-api bash`
7. `vendor/bin/phinx migrate`
8. `exit`
