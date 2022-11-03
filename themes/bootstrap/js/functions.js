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
 * @param {Event} event Element event handler.
 * @param {string} [checkboxId] The checkbox identifier. Optional if attached to
 * the checkbox itself.
 */
function toggleCheckboxCheck(event, checkboxId) {
	// Check if we are clicking inside or outside of the checkbox.
	if (checkboxId !== undefined) {
		// Click came from outside of the checkbox.
		var checkbox = $("#" + checkboxId);
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
 * @param {Element} elem The element to have the strikethrough class toggled.
 * @param {Event} [event] Element event handler.
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
 * @param {Event} event The event object of the element to be used to block.
 */
function preventDblClickHighlight(event) {
	if (event.detail <= 1)
		return;
	
	event.preventDefault();
}

/**
 * Toggles the visibility of the specified element.
 *
 * @param {string} elem XPath of the element to have its visibility toggled.
 * @param {Element} [sender] Element object that triggered this event to have
 *                           its innerHTML property changed.
 * @param {string} [moreMsg="More Information"] Message that the sender is
 * supposed to display for more information.
 * @param {string} [lessMsg="Less Information"] Message that the sender is
 * supposed to display for less information.
 */
function toggleElementVisibility(elem, sender, moreMsg, lessMsg) {
	moreMsg = (typeof moreMsg !== "undefined") ? moreMsg : "More Information";
	lessMsg = (typeof lessMsg !== "undefined") ? lessMsg : "Less Information";
	var elem = $(elem);

	// Toggle things around.
	if (elem.hasClass("d-none")) {
		// Turn visible.
		elem.removeClass("d-none");

		// Change the message in the sender element.
		if (sender !== undefined)
			sender.innerHTML = lessMsg;
	} else {
		// Turn invisible.
		elem.addClass("d-none");

		// Change the message in the sender element.
		if (sender !== undefined)
			sender.innerHTML = moreMsg;
	}
}

/**
 * Generates a random number between min (inclusive) and max (exclusive).
 *
 * @param {number} min The minimum, inclusive, number in the range.
 * @param {number} max The maximum, exclusive, number in the range.
 * @return {number} A random number in the specified range.
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
	var durationMillis = duration * 1000;
	var animationEnd = Date.now() + durationMillis;
	var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

	// Pop confetti at a pre-determined interval.
	var interval = setInterval(function() {
		// Check if we should end the animation.
		var timeLeft = animationEnd - Date.now();
		if (timeLeft <= 0) {
			return clearInterval(interval);
		}

		// Determine the amount of particles to use.
		var particleCount = 200 * (timeLeft / durationMillis);

		// Since particles fall down, start a bit higher than random.
		confetti(Object.assign({}, defaults, { particleCount,
			origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
		confetti(Object.assign({}, defaults, { particleCount,
			origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
	}, 250);
}

/**
 * Simulates the action of a user submitting a form dynamically.
 * 
 * @param {string} method HTTP method of the request.
 * @param {string} action Equivalent to the <form> action attribute.
 * @param {object} [params] Parameters of the request.
 */
function formSubmit(method, action, params) {
	// Create the form element.
	var form = document.createElement("form");
	form.method = method;
	form.action = action;

	if (params !== undefined) {
		// Go through the parameters.
		for (var key in params) {
			// Is this key actually ours?
			if (!params.hasOwnProperty(key))
				continue;

			// Build up the parameter hidden field.
			var hidden = document.createElement("input");
			hidden.type = "hidden";
			hidden.name = key;
			hidden.value = params[key];

			form.appendChild(hidden);
		}
	}

	// The form must be placed in the body before submitting.
	document.body.appendChild(form);
	form.submit();
}

// String.startsWith polyfill for older browsers.
if (!String.prototype.startsWith) {
	Object.defineProperty(String.prototype, 'startsWith', {
		value: function (search, rawPos) {
			var pos = rawPos > 0 ? rawPos | 0 : 0;
			return this.substring(pos, pos + search.length) === search;
		}
	});
}
