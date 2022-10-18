/**
 * storage/picklist.js
 * Handles the storage of the state of a pick list.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

"use strict";

/**
 * A class to handle the storage of the state of a pick list.
 * @constructor
 * 
 * @param {string} documentId ID "slug" that identifies the PickLE document.
 */
function PickListStorage(documentId) {
	this.documentId = documentId;
	this.storageKey = "pickstate_" + documentId;
	this.pickState = {
		components: []
	};
}

/**
 * Loads the pick list state from storage.
 */
PickListStorage.prototype.load = function () {
	var pickState = localStorage.getItem(this.storageKey);
	if (pickState !== null)
		this.pickState = JSON.parse(pickState);
};

/**
 * Saves the pick list state to storage.
 */
PickListStorage.prototype.save = function () {
	localStorage.setItem(this.storageKey, JSON.stringify(this.pickState));
};

/**
 * Clears the contents of the components array.
 */
PickListStorage.prototype.clearComponents = function () {
	this.pickState.components = [];
};

/**
 * Gets a component in storage by its ID.
 * 
 * @param {string} id Component ID.
 * @returns Component object in the list or null if one wasn't found.
 */
PickListStorage.prototype.getComponentById = function (id) {
	// Go through the component list trying to find the one we want.
	for (var i = 0; i < this.pickState.components.length; i++) {
		if (this.pickState.components[i].id === id) {
			return this.pickState.components[i];
		}
	}

	// Couldn't find a component with this ID.
	return null;
};

/**
 * Adds a component to the pick state list.
 * 
 * @param {string} id Component ID.
 * @param {boolean} picked Has this component been picked?
 * @param {Array<string>} placedRefDes List of the placed reference designators.
 * @param {boolean} [save=true] Should we save the changes automatically?
 */
PickListStorage.prototype.addComponent = function (id, picked, placedRefDes, save) {
	save = (typeof save !== "undefined") ? save : true;

	// Push the new component into the components state array.
	this.pickState.components.push({
		id: id,
		picked: picked,
		placedRefDes: placedRefDes
	});

	// Save changes automatically.
	if (save)
		this.save();
};

/**
 * Sets the picked state of a component.
 * 
 * @param {string} id Component ID.
 * @param {boolean} state Has this component been picked?
 * @param {boolean} [save=true] Should we save the changes automatically?
 */
PickListStorage.prototype.setComponentPicked = function (id, state, save) {
	save = (typeof save !== "undefined") ? save : true;

	// Check if we are just modifying a component we already have.
	var component = this.getComponentById(id);
	if (component !== null) {
		component.picked = state;
		if (save)
			this.save();

		return;
	}

	// Looks like we've got a brand new component.
	this.addComponent(id, state, [], save);
};

/**
 * Sets the placed state of a component reference designator.
 * 
 * @param {string} id Component ID.
 * @param {string} refdes Reference designator.
 * @param {boolean} state Has this component reference designator been placed?
 * @param {boolean} [save=true] Should we save the changes automatically?
 */
PickListStorage.prototype.setComponentRefDesPicked = function (id, refdes, state, save) {
	save = (typeof save !== "undefined") ? save : true;

	// Check if we are just modifying a component we already have.
	var component = this.getComponentById(id);
	if (component !== null) {
		var found = false;

		// Go through reference designators trying to find the one we want.
		for (var i = 0; i < component.placedRefDes.length; i++) {
			if (component.placedRefDes[i] === refdes) {
				found = true;
				if (!state)
					component.placedRefDes.splice(i, 1);
				
				break;
			}
		}

		// If we need to set a previously unplaced reference designator.
		if (!found && state)
			component.placedRefDes.push(refdes);

		// Automatically save if required.
		if (save)
			this.save();
		return;
	}

	// Looks like we've got a brand new component.
	this.addComponent(id, false, (state) ? [ refdes ] : [], save);
};

/**
 * Applies the loaded settings to the current page.
 */
PickListStorage.prototype.apply = function () {
	// Go through the components checking their checkboxes.
	for (var i = 0; i < this.pickState.components.length; i++) {
		var component = this.pickState.components[i];

		// Check the picked checkbox if applicable.
		var elem = $("#" + this.getComponentCheckboxId(component.id));
		elem.prop("checked", component.picked);

		// Go through the reference designators crossing them.
		for (var j = 0; j < component.placedRefDes.length; j++) {
			var refdes = component.placedRefDes[j];

			// Cross out the reference designator.
			elem = $("#" + this.getRefDesElementId(component.id, refdes));
			elem.addClass("refdes-crossed");
		}
	}
};

/**
 * Handles the frontend aspect of the user toggling the picked status of a
 * component.
 * 
 * @param {string} componentId ID of the component involved.
 * @param {Event} event Element event handler.
 */
PickListStorage.prototype.handleToggleComponentPicked = function (componentId, event) {
	var checkboxId = this.getComponentCheckboxId(componentId);

	toggleCheckboxCheck(event, checkboxId);
	this.setComponentPicked(componentId, $("#" + checkboxId).prop("checked"));
};

/**
 * Handles the frontend aspect of the user toggling the picked status of a
 * reference designator.
 * 
 * @param {string} componentId ID of the component involved.
 * @param {string} refdes Reference designator.
 * @param {Event} [event] Element event handler.
 */
PickListStorage.prototype.handleToggleRefDesPicked = function (componentId, refdes, event) {
	var elemId = this.getRefDesElementId(componentId, refdes);

	toggleStrikethrough(document.getElementById(elemId), event);
	this.setComponentRefDesPicked(componentId, refdes,
		$("#" + elemId).hasClass("refdes-crossed"));
};

/**
 * Gets the canonical ID of the component picked checkbox element.
 * 
 * @param {string} componentId ID of the component.
 * @returns {string} Canonical ID of the checkbox element.
 */
PickListStorage.prototype.getComponentCheckboxId = function (componentId) {
	return componentId + "-picked";
};

/**
 * Gets the canonical ID of the component reference designator element.
 * 
 * @param {string} componentId ID of the component.
 * @param {string} refdes Reference designator.
 * @returns {string} Canonical ID of the reference designator element.
 */
PickListStorage.prototype.getRefDesElementId = function (componentId, refdes) {
	return componentId + "-refdes-" + refdes.toLowerCase();
};

/**
 * Clears all of the picks from the page and in localStorage.
 * 
 * @param {boolean} [askConfirm=false] Ask the user for confirmation?
 */
PickListStorage.prototype.clearPicks = function (askConfirm) {
	askConfirm = (typeof askConfirm !== "undefined") ? askConfirm : false;

	// Confirm the action with the user.
	if (askConfirm) {
		if (!window.confirm("Are you sure you want to clear all picked items?"))
			return;
	}

	// Go through the components unchecking their checkboxes.
	for (var i = 0; i < this.pickState.components.length; i++) {
		var component = this.pickState.components[i];
		component.picked = false;

		// Check the picked checkbox if applicable.
		var elem = $("#" + this.getComponentCheckboxId(component.id));
		elem.prop("checked", component.picked);
	}

	// Save changes.
	this.save();
};

/**
 * Clears all of the reference designator placements from the page and in
 * localStorage.
 * 
 * @param {boolean} [askConfirm=false] Ask the user for confirmation?
 */
PickListStorage.prototype.clearPlacements = function (askConfirm) {
	askConfirm = (typeof askConfirm !== "undefined") ? askConfirm : false;

	// Confirm the action with the user.
	if (askConfirm) {
		if (!window.confirm("Are you sure you want to clear all placed items?"))
			return;
	}

	// Go through the components unchecking their checkboxes.
	for (var i = 0; i < this.pickState.components.length; i++) {
		var component = this.pickState.components[i];

		// Go through the reference designators un-crossing them.
		while (component.placedRefDes.length > 0) {
			// Get the first reference designator and remove it from the list.
			var refdes = component.placedRefDes.shift();

			// Clears the reference designator label.
			var elem = $("#" + this.getRefDesElementId(component.id, refdes));
			elem.removeClass("refdes-crossed");
		}
	}

	// Save changes.
	this.save();
};
