<?php
/**
 * functions.php
 * Provides a whole bunch of handy functions to work inside of HTML files.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

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
