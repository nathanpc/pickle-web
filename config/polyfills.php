<?php
/**
 * polyfills.php
 * PHP 8 brought a whole bunch of awesome functions, but not everyone is able to
 * enjoy them b default.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

if (!function_exists('str_contains')) {
	/**
	 * Determine if a string contains a given substring. (Polyfill from the
	 * Laravel folks)
	 *
	 * @param  string $haystack The string to search in.
	 * @param  string $needle   The substring to search for in the haystack.
	 * @return bool             true if needle is in haystack, false otherwise.
	 */
	function str_contains($haystack, $needle) {
		return $needle !== '' && mb_strpos($haystack, $needle) !== false;
	}
}

if (!function_exists('str_ends_with')) {
	/**
	 * Checks if a string ends with a given substring.
	 * @link https://www.php.net/manual/en/function.str-ends-with.php#126551
	 *
	 * @param  string $haystack The string to search in.
	 * @param  string $needle   The substring to search for in the haystack.
	 * @return bool             true if haystack ends with needle, false otherwise.
	 */
	function str_ends_with($haystack, $needle) {
		$needle_len = strlen($needle);
		return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, -$needle_len));
	}
}