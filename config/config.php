<?php
/**
 * config.php
 * The application configuration file.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

 /**
  * Defines a configuration constant to be used in the application from an
  * environment variable of the same name. If the environment variable was not
  * set it uses a default value instead.
  *
  * @param string $name    Name of the environment variable.
  * @param any    $default Default value if the environment variable wasn't set.
  * @param string $varname Constant name to be used if it should be different
  *                        than the environment variable name.
  */
function env_define_const($name, $default, $varname = null) {
	// If a constant name wasn't passed use the environment variable name.
	if (is_null($varname))
		$varname = $name;

	// Define the constant.
	define($varname, (getenv($name) === false) ? $default : getenv($name));
}

// General application branding.
env_define_const('PICKLE_APP_NAME', 'PickLE', 'APP_NAME');
env_define_const('PICKLE_APP_DEFAULT_THEME', 'bootstrap');

// PickLE Perl microservice.
env_define_const('PICKLE_API_PROTOCOL', 'http');
env_define_const('PICKLE_API_HOST', 'parser');
env_define_const('PICKLE_API_PORT', '3000');
define('PICKLE_API_URL',
	PICKLE_API_PROTOCOL . '://' . PICKLE_API_HOST . ':' . PICKLE_API_PORT);
