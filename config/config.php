<?php
/**
 * config.php
 * The application configuration file.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

// General application branding.
define('APP_NAME', 'PickLE');

// PickLE Perl microservice.
define('PICKLE_API_PROTOCOL', 'http');
define('PICKLE_API_HOST', 'api');
define('PICKLE_API_PORT', '3000');
define('PICKLE_API_URL',
	PICKLE_API_PROTOCOL . '://' . PICKLE_API_HOST . ':' . PICKLE_API_PORT);
