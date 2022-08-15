# 🥒 PickLE

A web application (PHP) and a parsing client (Perl) to work PickLE archives, which is an electronic component pick list file format designed to be human-readable, and completely usable in its own plain-text form.

## Getting Started

Since this project contains a frontend and a backend parsing service the best way to run it is to create a simple `docker-compose.yml` that looks a bit like this:

```yaml
version: '3'

services:
  frontend:
    image: nathanpc/pickle:webapp
    depends_on:
      - parser
    restart: unless-stopped
    ports:
      - 80:80
    volumes:
      - ./resources:/var/www/app/resources
  parser:
    image: nathanpc/pickle:perl
    restart: unless-stopped
```

Then just run the project using `compose` like this:

```shell
docker compose up -d
```

### Volumes

- `/var/www/app/resources/pkl` - Where you should store your PickLE archives that are stored on the server.

## Find Us

* [GitHub](https://github.com/nathanpc/pickle-web)
