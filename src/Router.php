<?php
/**
 * Router.php
 * A very simple router to enable us to have theming support. May come in handy
 * in the future for other stuff.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/functions.php";
require_once __DIR__ . "/../vendor/autoload.php";

class Router {
	private $path;
	private $theme;

	/**
	 * Constructs a routing object.
	 *
	 * @param string $path  Path to the requested file.
	 * @param string $theme Current theme name.
	 */
	public function __construct($path, $theme = null) {
		$this->set_path($path);

		// Should we get our theme from the context?
		if (is_null($theme)) {
			$this->set_theme_from_context();
			return;
		} else {
			$this->set_theme($theme, false);
		}
	}

	/**
	 * Render the requested page or show an error page in case something
	 * happened or the page didn't exist.
	 */
	public function render_page() {
		// Check if the requested file exists and error out if needed.
		if (!$this->file_exists()) {
			$this->render_error_page();
			return;
		}

		// If we aren't requesting a PHP script, first set the content type.
		$file_ext = pathinfo($this->get_file_path(), PATHINFO_EXTENSION);
		if ($file_ext != "php") {
			$mime = new \Mimey\MimeTypes;
			header("Content-Type: " . $mime->getMimeType($file_ext));
		}

		// Render the requested page.
		require $this->get_file_path();
	}

	/**
	 * Renders an error page.
	 */
	protected function render_error_page() {
		// Set response code and content type header.
		http_response_code(404);
		header("Content-Type: text/plain");

		// Render a simple error message.
		echo "Couldn't find '" . $this->get_request_path() . "'\n";
	}

	/**
	 * Checks if the requested file actually exists.
	 *
	 * @return bool Does the requested file exist in the current theme?
	 */
	public function file_exists() {
		return file_exists($this->get_file_path());
	}

	/**
	 * Gets the clean requested path the browser asked us for.
	 *
	 * @return string Clean requested path.
	 */
	public function get_request_path() {
		return $this->path;
	}

	/**
	 * Gets the internal file system path related to the browser requested page.
	 *
	 * @return string Requested file path in the file system.
	 */
	public function get_file_path() {
		return __DIR__ . "/../themes/" . $this->get_theme() . "/" .
			$this->get_request_path();
	}

	/**
	 * Sets the requested path to route to.
	 *
	 * @param string $path Path the browser requested from us.
	 */
	public function set_path($path) {
		// Clean up the path just in case someone tries some funny stuff.
		$this->path = preg_replace('/\.\.[\\/]/', "", $path);
	}

	/**
	 * Gets the current theme name.
	 *
	 * @return string Current theme name.
	 */
	public function get_theme() {
		return $this->theme;
	}

	/**
	 * Sets the current theme name.
	 *
	 * @param string $name New theme name.
	 * @param bool   $save Should we also save the theme in the cookies?
	 */
	public function set_theme($name, $save = true) {
		// Clean up the theme name before setting it.
		$this->theme = preg_replace('/[^A-Za-z]/', "", $name);

		// Store the set theme in the browser cookies.
		if ($save)
			$this->update_cookie(true);
	}

	/**
	 * Sets the current theme based on the browser/session context.
	 */
	public function set_theme_from_context() {
		// Check if we are setting a theme.
		if (isset($_GET["theme"])) {
			$this->set_theme($_GET["theme"]);
			return;
		}

		// Check if we have an active theme set.
		if (isset($_COOKIE["theme"])) {
			$this->set_theme($_COOKIE["theme"], false);
			return;
		}

		// Use the default theme as a fallback.
		$this->set_theme(PICKLE_APP_DEFAULT_THEME);
	}

	/**
	 * Updates the cookies on the browser, if required, according to the current
	 * request.
	 *
	 * @param bool Should we update the cookies no matter what?
	 */
	protected function update_cookie($force = false) {
		if (isset($_GET["theme"]) || $force) {
			setcookie("theme", $this->theme);
		}
	}
}
