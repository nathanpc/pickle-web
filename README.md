# 🥒 PickLE Web Frontend

A web application to work PickLE archives, which is an electronic component
pick list file format designed to be human-readable, and completely usable in
its own plain-text form.

## Setup Website

### Using Docker

The preferred way to run this web service is using [Docker](https://www.docker.com/),
since it's a lot easier to setup, maintain, and is the way we also run it, so
it's guaranteed to be tested.

To make things even easier for you we provide a `docker-compose` configuration,
in which all you have to do is configure which port you want the container to
listen from. After that it's as simple as running:

```bash
docker-compose up -d
```

All done!

### Manual Method

This web application uses a separate Perl project to parse documents and do
various operations related to text processing, so before continuing make sure
you have [PickLE🥒](https://github.com/nathanpc/pickle) up and running in web
server mode, which should be as simple as running `picklews` after fetching all
of the dependencies as described in that project's
[README](https://github.com/nathanpc/pickle/blob/master/README.pod).

After we have our parser microservice up and running, ensure that `.htaccess`
can be overridden in your web server by this project in order for us to
properly rewrite URLs.

Setting up this project on your web server is quite simple. Start by placing it
in an appropriate directory inside your server's `htdocs` folder and run the
following command inside the root of the project directory in order to install
all of the required packages:

```bash
composer install
```

Now that you have everything needed to run this project you need to make a
choice if you want to setup a [Virtual Host](https://httpd.apache.org/docs/2.4/vhosts/examples.html)
to point to your PickLE instance or just run it from a sub-directory in the
root of your web server.

If you choose to run this instance from a sub-directory, make sure that your
server is configured to allow the project to use its `.htaccess` file in order
to properly rewrite URLs and prevent access to sensitive directories. You can
learn how to enable this functionality from the following tutorial:
[How to Set Up the htaccess File on Apache](https://www.linode.com/docs/guides/how-to-set-up-htaccess-on-apache/)

If you choose to run your instance using Virtual Hosts, make sure that the
`DocumentRoot` property points to the `public/` directory of project. You can
learn how to set this up from the following tutorial:
[How To Set Up Apache Virtual Hosts](https://www.digitalocean.com/community/tutorial_collections/how-to-set-up-apache-virtual-hosts).
For completeness, here's an example of how your virtual host configuration
might look like:

```apacheconf
<VirtualHost *:80>
    ServerAdmin admin@pickle.local
    ServerName pickle.local
    DocumentRoot /var/www/pickle/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

<Directory /var/www/pickle/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Finally make sure to set the environment variables with the appropriate
parameters regarding your website folder structure and any other options you
might want to customize.

## Environment Variables

These are all of the environment variables that can be set in order to alter
the configuration of the application:

| Environment Variable | Default | Description |
|--|--|--|
| `PICKLE_APP_NAME` | `PickLE` | Application name branding (basically changes the titles) |
| `PICKLE_API_PROTOCOL` | `http` | Protocol used by the frontend to communicate with the backend parser |
| `PICKLE_API_HOST` | `parser` | Hostname/IP address of the backend parser |
| `PICKLE_API_PORT` | `3000` | Port the backend parser is listening on |

## Requirements

This project only depends on the basic stuff for modern PHP development and a
most likely the Perl interpreter that came with your distribution:

- [Apache](https://httpd.apache.org/) ^2.4
- [mod_rewrite](https://httpd.apache.org/docs/2.4/mod/mod_rewrite.html)
- [PHP](https://www.php.net/downloads.php#v7.4.13) ^7.4
- [Composer](https://getcomposer.org/download/) ^2.0
- [Perl](https://www.perl.org/) ^5.10

## License

This project is licensed under the **MIT License**.
