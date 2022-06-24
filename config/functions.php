<?php
/**
 * functions.php
 * Provides a whole bunch of handy functions to work inside of HTML files.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once __DIR__ . "/config.php";

/**
 * Creates a simple, but effective, title string.
 *
 * @param  string $desc An optional descriptor of the current page.
 * @return string       Formatted title.
 */
function site_title($desc = NULL) {
	// Default to just the application name.
	$title = APP_NAME;
	
	// Prepend a description if the user wants.
	if (!is_null($desc))
		$title = $desc . ' - ' . $title;
	
	return $title;
}

/**
 * Creates a proper href location based on our project's root path.
 *
 * @param  string $loc Location as if the resource was in the root of the server.
 * @return string      Transposed location of the resource.
 */
function href($loc) {
	return SITE_URL . $loc;
}

/**
 * Gets the value of an URL parameter or uses a default if one wasn't provided.
 * 
 * @param  string $name    Parameter name (key in $_GET).
 * @param  any    $default Default value in case the parameter wasn't set.
 * @return any             Parameter value provided or the default.
 */
function urlparam($name, $default = NULL) {
	// Should we use the default value?
	if (!isset($_GET[$name]))
		return $default;

	// We've got it.
	return $_GET[$name];
}
