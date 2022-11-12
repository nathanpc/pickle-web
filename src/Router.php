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
	const PRIVATE_DIR = "/private";
	const TEMPLATE_DIR = self::PRIVATE_DIR . "/templates";

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
		// Ensure that we have the router variable available for templates.
		$router = $this;

		// Check if the requested file exists or if it's protected.
		if (!$this->file_exists() || $this->is_protected_path()) {
			// Render the Not Found error page.
			$this->render_error_page("not_found", array(
				"PAGE_TITLE" => "Page Not Found",
				"ERROR_MESSAGE" => "Couldn't find the requested page '" .
					$this->get_request_path() . "'"
			));

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
	 * Renders a standardized error page in case something bad happens.
	 *
	 * @param  string $page Name of the template page in 'error_pages' to be rendered.
	 * @param  array  $vars Array with the ('VARIABLE_NAME', 'VARIABLE_VALUE') pairs.
	 */
	public function render_error_page($page, $vars) {
		// Define the variables for the error page template.
		foreach ($vars as $key => $value) {
			define($key, $value);
		}

		// Render the error page.
		require $this->get_template_file_path("/error_pages/$page.php");
	}

	/**
	 * Gets the internal file system path related to the browser requested page.
	 *
	 * @param string $path Clean path to require the underlying file. If NULL or
	 *                     nothing is passed this will use the stored requested
	 *                     path.
	 *
	 * @return string Requested file path in the file system.
	 */
	public function get_file_path($path = null) {
		// Should we use the requested path?
		if (is_null($path))
			$path = $this->get_request_path();

		// Build up a file system path.
		return __DIR__ . "/../themes/" . $this->get_theme() . "/$path";
	}

	/**
	 * Gets the file system path for a template from the current theme.
	 *
	 * @param string $path Clean path relative to the template directory inside
	 *                     inside of the theme directory.
	 *
	 * @return string File system path inside the theme template directory.
	 */
	protected function get_template_file_path($path) {
		return $this->get_file_path(self::TEMPLATE_DIR . "/$path");
	}

	/**
	 * Checks if the requested file actually exists.
	 *
	 * @return boolean Does the requested file exist in the current theme?
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
	 * Checks if the requested page is considered to be protected.
	 *
	 * @return boolean Is the requested page protected?
	 */
	public function is_protected_path() {
		return preg_match("/^\/?private/i", $this->get_request_path()) == 1;
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
	 * @param string  $name New theme name.
	 * @param boolean $save Should we also save the theme in the cookies?
	 */
	public function set_theme($name, $save = true) {
		// Clean up the theme name before setting it.
		$this->theme = preg_replace('/[^A-Za-z0-9\-_]/', "", $name);

		// Store the set theme in the browser cookies.
		if ($save)
			$this->update_cookie(true);
	}

	/**
	 * Sets the current theme based on the browser/session context.
	 */
	public function set_theme_from_context() {
		// Check if we are setting a theme from a parameter.
		if (isset($_GET["theme"])) {
			$this->set_theme($_GET["theme"]);
			return;
		}

		// Check if we are setting a theme using the quick change widget.
		if (isset($_POST["theme"])) {
			$this->set_theme($_POST["theme"]);
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
	 * @param boolean Should we update the cookies no matter what?
	 */
	protected function update_cookie($force = false) {
		if (isset($_GET["theme"]) || $force) {
			setcookie("theme", $this->theme, 0, "/");
		}
	}
}
