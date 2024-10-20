# Fidelify API - Terraform

## Installation

> Docker required: [how to install](https://docs.docker.com/engine/install/ubuntu/).

#### AWS IAC build

> For windows, must be on prompt.
> Replace <project-dir> with your project directory path.
> Replace <user-dir> with your user home directory path.

1. `docker run --rm -it -v <project-dir>/.devops/terraform:/terraform -v <user-dir>/.aws:/root/.aws -w /terraform hashicorp/terraform:latest <command>`
