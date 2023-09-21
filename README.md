# Fidelify API

## Installation

> Docker required: [how to install](https://docs.docker.com/engine/install/ubuntu/).

1. `docker build -t swoole-server .`
2. `docker run -it -v $(pwd):/usr/src/swoole-server -p 8003:8003 --rm --name fidelify-api swoole-server php public/index.php`
