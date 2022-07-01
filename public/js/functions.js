/**
 * functions.js
 * A collection of handy functions to help out make the web page a bit more
 * interactive.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

/**
 * Toggles a checkbox checked state.
 *
 * @param {string} checkbox_id  The checkbox identifier.
 */
var toggle_checkbox = function (checkbox_id) {
	var checkbox = $("#" + checkbox_id);
	checkbox.prop("checked", !checkbox.prop("checked"));
}
