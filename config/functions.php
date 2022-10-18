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
 * @param  string $desc An optional descriptor of the current page. This will be
 *                      automatically substituted by a PAGE_TITLE constant if
 *                      it is defined.
 * @return string       Formatted title.
 */
function site_title($desc = NULL) {
	// Default to just the application name.
	$title = APP_NAME;

	// Check if we should use the PAGE_TITLE constant.
	if (defined('PAGE_TITLE') && is_null($desc))
		$desc = constant('PAGE_TITLE');
	
	// Prepend a description if the user wants.
	if (!is_null($desc))
		$title = $desc . ' - ' . $title;
	
	return $title;
}

/**
 * Checks if a parent page name matches the current page name.
 * 
 * @param  string  $parent Parent page script name without the extension.
 * @return boolean         Are the page names the same?
 */
function is_parent_page($parent) {
	return basename($_SERVER['PHP_SELF'], '.php') == $parent;
}

/**
 * Creates a proper href location based on our project's root path.
 *
 * @param  string $loc Location as if the resource was in the root of the server.
 * @return string      Transposed location of the resource.
 */
function href($loc) {
	return $loc;
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

/**
 * Automatically generate a link if a string is a URL.
 * 
 * @param  string $str String to be checked for an URL.
 * @return string      Same string if it's not a URL. Otherwise an anchor tag.
 */
function auto_link($str) {
	// Check if we actually have an URL.
	if (!preg_match('/^[A-Za-z]+:(\/\/)?[A-Za-z0-9]/', $str))
		return $str;

	// Parse the URL. If it's seriously malformed just return the string.
	$url = parse_url($str);
	if ($url === false)
		return $str;

	// Build up an URL.
	$str_url = ((isset($url['scheme'])) ? '' : 'https://') . $str;
	$pretty_url = $url['host'] . ((isset($url['path'])) ? $url['path'] : '');

	return '<a href="' . $str_url . '">' . $pretty_url . '</a>';
}

/**
 * Generates a Bootstrap Navbar item.
 * 
 * @param  string $label    Label of the item.
 * @param  string $href     Relative URL this item points to or a full URL.
 * @param  string $pagename Destination page script name without the extension.
 * @return string           Fully-populated Bootstrap navbar item.
 */
function nav_item($label, $href, $pagename) {
	// Are we the current page?
	$current = is_parent_page($pagename);

	// Make sure we deal with relative URLs.
	if ($href[0] == '/')
		$href = href($href);

	// Build up some HTML.
	return '<li class="nav-item' . (($current) ? ' active' : '') . '"><a class="nav-link" href="' . $href . '">' . $label . (($current) ? ' <span class="sr-only">(current)</span>' : '') . '</a></li>';
}

/**
 * Gets a {@see PickLE\Document} from the data gathered from the request.
 *
 * @return PickLE\Document Pick list archive or NULL.
 */
function get_picklist_from_req() {
	// Are we just picking an stored archive?
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
		return PickLE\Document::FromArchive(urlparam('archive', NULL));
	
	// Make sure there's no funny stuff going on..
	if ($_SERVER['REQUEST_METHOD'] != 'POST')
		return NULL;
	
	// Determine the correct way to parse this archive.
	if (isset($_POST['archive-text'])) {
		// User submitted the archive in text form.
		return PickLE\Document::FromString($_POST['archive-text']);
	} else if (isset($_FILES['archive-file'])) {
		// User submitted the archive in file form.
		return PickLE\Document::FromFile($_FILES['archive-file']['tmp_name']);
	}

	// Ok...
	return NULL;
}

/**
 * Renders a standardized error page in case something bad happens.
 *
 * @param  string $page Name of the template page in 'error_pages' to be rendered.
 * @param  array  $vars Array with the ('VARIABLE_NAME', 'VARIABLE_VALUE') pairs.
 * @return string       Whatever the page 'require' returned.
 */
function render_error_page($page, $vars) {
	// Define the variables.
	foreach ($vars as $key => $value) {
		define($key, $value);
	}

	// Render the error page.
	return require(get_theme_path() . "/private/templates/error_pages/$page.php");
}

/**
 * Gets the currently active theme name.
 *
 * @return string Currently active theme name.
 */
function get_theme_name() {
	// Check if we are setting a theme.
	if (isset($_GET["theme"])) {
		setcookie("theme", $_GET["theme"]);
		return preg_replace('/[^A-Za-z]/', "", $_GET["theme"]);
	}

	// Check if we have an active theme set.
	if (isset($_COOKIE["theme"]))
		return preg_replace('/[^A-Za-z]/', "", $_COOKIE["theme"]);

	// Use the default determined by the configuration.
	return PICKLE_APP_DEFAULT_THEME;
}

/**
 * Gets the path to a theme base directory.
 *
 * @param string $theme_name Name of the theme to be used. If one isn't supplied
 *                           we'll use the currently active theme name.
 *
 * @return string Path to the theme's base directory.
 */
function get_theme_path($theme_name = NULL) {
	// Use the currently active theme if one wasn't supplied.
	if (is_null($theme_name))
		$theme_name = get_theme_name();

	return __DIR__ . "/../themes/$theme_name";
}
