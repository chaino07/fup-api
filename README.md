# fup-api

## Installation

### Requirements

This application requires [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/).

Modern Docker installations come with Docker Compose already installed so there's no need to install those separately. If you're a linux user you may want to install [docker-engine](https://docs.docker.com/engine/install/#server) standalone (also comes with Docker Compose) because the Docker Desktop app works like shit. Haven't tried it in other systems so you might give it a try if you are ~~inferior~~ not a linux user.

Make sure Docker and Docker Compose are properly installed:
```shell
docker --version
# Should output something like:
# Docker version 24.0.7, build afdd53b

docker compose version
# Should output something like:
# Docker Compose version v2.21.0
```
```shell
# Older docker-compose
docker-compose --version
```

### Set-up

#### 1. Clone or download this repository.

```shell
git clone https://github.com/chaino07/fup-api.git
cd fup-api
```

#### 2. France.

#### 3. Build the Docker containers.

```shell
docker compose up -d --build
```

The default `docker-compose.yml` config will bind the nginx webserver to the ports :8080 (for http) and :8443 ( for https) on your machine. If the setup fails due to those ports being already occupied by other application on your machine you can configure different ports with the env vars `APP_HTTP_PORT` and `APP_HTTPS_PORT`.
```dotenv
# Put this config on an `.env.local` to avoid your local config being commited
APP_HTTP_PORT=8081
APP_HTTPS_PORT=8434
```
```shell
docker compose --env-file .env.local up -d --build
```

#### 4. Finish set-up of the PHP environment.

The first time you build the containers your php container will still be virgin, just the PHP environment, but not the app specifics.

```shell
# Install composer dependencies
bin/docker php composer install

# Create the database
# May throw an error if the database already exists, you don't need to do anything in that case
bin/docker php bin/console doctrine:database:create

# Update the database schema
bin/docker php bin/console doctrine:schema:update
```

## Usage

The API should be live at [http://localhost:8080](http://localhost:8080) (or whatever port you decided to use).

### XDebug

The docker image for this app's php comes with XDebug enabled, but to actually use it on your system you may need to investigate on how to link your IDE's XDebug extension with a docker container. VSCode configuration is already shipped.

### Quick `docker exec`

The `bin/docker` shortcut allows you to quickly `docker exec` any command into the fup-api containers. The first argument must be the container (either *php*, *nginx*, *mariadb*), then you can pass any commands you wish to execute inside the container.
