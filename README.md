# Fidelify API

## Installation

> Docker required: [how to install](https://docs.docker.com/engine/install/ubuntu/).

1. `docker build -t fidelify/phpswoole .`
2. `docker run -it -v $(pwd):/api -p 8003:8003 --rm --name fidelify-api fidelify/phpswoole php public/index.php`
