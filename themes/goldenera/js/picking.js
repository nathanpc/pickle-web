/**
 * picking.js
 * Handles component picking and styling.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

"use strict";

/**
 * Toggles a checkbox checked state.
 *
 * @param {Event} event Element event handler.
 * @param {string} [checkboxId] The checkbox identifier. Optional if attached to
 * the checkbox itself.
 */
function toggleCheckboxCheck(event, checkboxId) {
	// Check if we are clicking outside of the checkbox.
	if (checkboxId !== undefined) {
		var checkbox = document.getElementById(checkboxId);

		if (checkbox.getAttribute("checked")) {
			checkbox.removeAttribute("checked");
		} else {
			checkbox.setAttribute("checked", "true");
		}

		return;
	}

	// Looks like we are clicking the checkbox.
	event.stopPropagation();
}

/**
 * Toggles the strikethrough style of an element.
 *
 * @param {Element} elem The element to have the strikethrough class toggled.
 * @param {Event} [event] Element event handler.
 */
function toggleStrikethrough(elem, event) {
	if (elem.className.indexOf("strikethrough") !== -1) {
		// Toggle to not have the strikethrough.
		elem.className = elem.className.replace("strikethrough", "");
	} else {
		// Toggle strikethrough ON.
		elem.className += " strikethrough";
	}

	// Block the propagation?
	if (event !== undefined)
		event.stopPropagation();
}

/**
 * When attached to the "ondblclick" event of an element, you won't be able to
 * highlight the text ONLY when double-clicking it. This is useful for text
 * elements with "onclick" events.
 *
 * @param {Event} event The event object of the element to be used to block.
 */
function preventDblClickHighlight(event) {
	event.preventDefault();
}
