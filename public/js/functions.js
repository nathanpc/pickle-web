/**
 * functions.js
 * A collection of handy functions to help out make the web page a bit more
 * interactive.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

"use strict";

/**
 * Toggles a checkbox checked state.
 *
 * @param {string} checkbox_id  The checkbox identifier.
 */
function toggleCheckboxCheck(checkbox_id) {
	var checkbox = $("#" + checkbox_id);
	checkbox.prop("checked", !checkbox.prop("checked"));
}

/**
 * Toggles the strikethrough style of an element.
 *
 * @param {element} elem  The element to have the strikethrough class toggled.
 * @param {event}   event Event handler for the function. Optional.
 */
function toggleStrikethrough(elem, event) {
	$(elem).toggleClass("refdes-crossed");
	
	// Block the propagation?
	if (event !== undefined)
		event.stopPropagation();
}

/**
 * When attached to the "mousedown" event of an element, you won't be able to
 * highlight the text ONLY when double-clicking it. This is useful for text
 * elements with "onclick" events.
 *
 * @param {event} event The event object of the element to be used to block.
 */
function preventDblClickHighlight(event) {
	if (event.detail <= 1)
		return;
	
	event.preventDefault();
}
