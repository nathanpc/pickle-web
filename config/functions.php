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
		$title = "$desc  - $title";

	return $title;
}

/**
 * Checks if a parent page name matches the current page name.
 *
 * @param  string  $parent Parent page script name without the extension.
 * @return boolean         Are the page names the same?
 */
function is_parent_page($parent) {
	return basename($_GET['path'], '.php') == $parent;
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

	return "<a href=\"$str_url\">$pretty_url</a>";
}

/**
 * Gets a {@see PickLE\Document} from the data gathered from the request.
 *
 * @return PickLE\Document Pick list archive or NULL.
 */
function get_picklist_from_req() {
	$picklist = NULL;

	// Are we just picking an stored archive?
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$picklist = PickLE\Document::FromArchive(urlparam('archive', NULL));
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Check if we are in fact trying to delete the archive.
		if (isset($_GET['delete'])) {
			// Check if the user is allowed to delete things from the server.
			if (!is_server_upload_enabled()) {
				throw new \Exception("Archive deletions are disabled on this " .
					"server. In order to enable them set the " .
					"<code>PICKLE_ENABLE_SERVER_UPLOAD</code> environment " .
					"variable to <code>true</code>.");
			}

			// Get the archive and check if it is valid.
			$picklist = PickLE\Document::FromArchive(urlparam('delete', NULL));
			if (is_null($picklist))
				throw new \Exception("No valid archive was found to be deleted.");

			// Delete the archive and redirect the user to the archives page.
			$picklist->delete();
			header("Location: /archive", true, 302);
			die();
		}

		// Determine the correct way to parse this archive.
		if (isset($_POST['archive-text'])) {
			// User submitted the archive in text form.
			$picklist = PickLE\Document::FromString($_POST['archive-text']);
		} else if (isset($_FILES['archive-file'])) {
			// User submitted the archive in file form.
			$picklist = PickLE\Document::FromFile($_FILES['archive-file']['tmp_name']);
		} else {
			// You need to specify something!
			throw new \Exception("No valid archive source was provided.");
		}

		// Are we supposed to save this file to the server?
		if (isset($_GET['upload'])) {
			// Check if the user is allowed to do so.
			if (!is_server_upload_enabled()) {
				throw new \Exception("Archive uploads are disabled on this " .
					"server. In order to enable them set the " .
					"<code>PICKLE_ENABLE_SERVER_UPLOAD</code> environment " .
					"variable to <code>true</code>.");
			}

			// Set the archive name according to what was given to us.
			if (isset($_POST['name']))
				$picklist->set_archive_name($_POST['name']);

			// Save the archive to the server.
			$picklist->save();

			// Redirect the user to the new archive page.
			header("Location: " . $picklist->get_pick_url(), true, 302);
			die();
		}
	} else {
		// Looks like some funny business is going on...
		throw new \Exception("Invalid request method <code>" .
			$_SERVER['REQUEST_METHOD'] . "</code>.");
	}

	return $picklist;
}

/**
 * Checks for 'booleanic' values.
 *
 * @param any $value Value to be checked for booleaness.
 * @return boolean Returns TRUE for "1", "true", "on" and "yes". FALSE for "0",
 *                 "false", "off" and "no".
 *
 * @see https://www.php.net/manual/en/function.is-bool.php#124179
 */
function is_enabled($value) {
	// Do a proper boolean conversion.
	return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/**
 * Checks if server uploads are enabled according to the configuration.
 *
 * @return boolean Are server uploads enabled?
 */
function is_server_upload_enabled() {
	return is_enabled(PICKLE_ENABLE_SERVER_UPLOAD);
}
