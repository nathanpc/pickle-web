# 🥒 PickLE for PHP

A library and web utility to deal with [PickLE🥒](https://github.com/nathanpc/pickle)
documents using PHP.


## Setup Website

First make sure that `.htaccess` can be overriden by this project in order to
properly rewrite the website URLs.



Setting up this project on your web server is quite simple. Start by placing it
in an appropriate directory inside your server's `htdocs` folder and run the
following command inside the root of the project directory in order to install
all of the required packages:

```bash
composer install
```

Now that you have everything needed to run this project you need to make a
choice if you want to setup a [Virtual Host](https://httpd.apache.org/docs/2.4/vhosts/examples.html)
to point to your PickLE instance or just run it from a sub-directory in the root
of your webserver.

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

Finally make sure to edit the `config/config.php` file with the appropriate
parameters regarding your website folder structure and any other options you
might want to customize.


## Requirements

This is a simple project and only depends on the basic stuff for modern PHP
development:

  - [Apache](https://httpd.apache.org/) ^2.4
  - [PHP](https://www.php.net/downloads.php#v7.4.13) ^7.4
  - [Composer](https://getcomposer.org/download/) ^2.0


## License

This project is licensed under the **MIT License**.
