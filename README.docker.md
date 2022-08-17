# 🥒 PickLE

A web application (PHP) and a parsing client (Perl) to work PickLE archives, which is an electronic component pick list file format designed to be human-readable, and completely usable in its own plain-text form.

## Getting Started

Since this project contains a frontend and a backend parsing service the best way to run it is to create a simple `docker-compose.yml` that looks a bit like this:

```yaml
version: '3'

services:
  frontend:
    image: nathanpc/pickle:web-frontend
    depends_on:
      - parser
    restart: unless-stopped
    ports:
      - 80:80
    volumes:
      - ./resources:/var/www/app/resources
  parser:
    image: nathanpc/pickle:parser-api
    restart: unless-stopped
```

Then just run the project using `compose` like this:

```shell
docker compose up -d
```

### Environment Variables

These are all of the environment variables that can be set in order to alter
the configuration of the application:

| Environment Variable | Default | Description |
|--|--|--|
| `PICKLE_APP_NAME` | `PickLE` | Application name branding (basically changes the titles) |
| `PICKLE_API_PROTOCOL` | `http` | Protocol used by the frontend to communicate with the backend parser |
| `PICKLE_API_HOST` | `parser` | Hostname/IP address of the backend parser |
| `PICKLE_API_PORT` | `3000` | Port the backend parser is listening on |

### Volumes

- `/var/www/app/resources/pkl` - Where you should store your PickLE archives that are stored on the server.

## Find Us

* [GitHub](https://github.com/nathanpc/pickle-web)
