# Fidelify API

## Installation

> Require docker, [how to install](https://docs.docker.com/engine/install/ubuntu/).

1. `docker build -t swoole-server .`
2. `docker run -it -v "$PWD":/usr/src/fidelify-api -p 8003:8003 --rm --name fidelify-api server`
