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
 * @param {event}  event        Element event handler.
 * @param {string} checkbox_id  The checkbox identifier. Should be {undefined}
 *                              if attached to the checkbox itself.
 */
function toggleCheckboxCheck(event, checkbox_id) {
	// Check if we are clicking inside or outside of the checkbox.
	if (checkbox_id !== undefined) {
		// Click came from outside of the checkbox.
		var checkbox = $("#" + checkbox_id);
		checkbox.prop("checked", !checkbox.prop("checked"));
	} else {
		// Looks like we are clicking the checkbox.
		event.stopPropagation();
	}

	// Check if we have picked everything and is time to throw some confetti.
	if ($(".chk-picked").not(":checked").length === 0)
		congratsConfetti(5);
}

/**
 * Toggles the strikethrough style of an element.
 *
 * @param {element} elem  The element to have the strikethrough class toggled.
 * @param {event}   event Element event handler. Optional.
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

/**
 * Generates a random number between min (inclusive) and max (exclusive).
 *
 * @param  {number} min The minimum, inclusive, number in the range.
 * @param  {number} max The maximum, exclusive, number in the range.
 * @return {number}     A random number in the specified range.
 */
function randomInRange(min, max) {
	return Math.random() * (max - min) + min;
}

/**
 * Throws some congratulatory confetti.
 * 
 * @param {number} duration Duration of the animation in seconds.
 */
function congratsConfetti(duration) {
	var duration_ms = duration * 1000;
	var animationEnd = Date.now() + duration_ms;
	var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

	// Pop confetti at a pre-determined interval.
	var interval = setInterval(function() {
		// Check if we should end the animation.
		var timeLeft = animationEnd - Date.now();
		if (timeLeft <= 0) {
			return clearInterval(interval);
		}

		// Determine the amount of particles to use.
		var particleCount = 200 * (timeLeft / duration_ms);

		// Since particles fall down, start a bit higher than random.
		confetti(Object.assign({}, defaults, { particleCount,
			origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
		confetti(Object.assign({}, defaults, { particleCount,
			origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
	}, 250);
}
