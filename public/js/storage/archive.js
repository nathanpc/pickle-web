/**
 * storage/archive.js
 * Handles the storage of the user's archives.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

"use strict";

/**
 * A class to handle the storage of user's archives.
 * @constructor
 * 
 * @param {string} documentId ID "slug" that identifies the PickLE document.
 */
function ArchiveStorage(documentId) {
	this.documentId = documentId;
	this.storageKey = "archive_" + documentId;
	this.archive = {
		id: documentId,
		name: null,
		description: null,
		revision: null,
		file: ""
	};
}

/**
 * Gets a list of the archives available in storage.
 * 
 * @returns {Array<ArchiveStorage>} List of archives.
 */
ArchiveStorage.list = function () {
	var archiveList = [];
	for (var i = 0; i < localStorage.length; i++) {
		// Check if the key is an archive.
		var key = localStorage.key(i);
		if (!key.startsWith("archive_"))
			continue;

		// Build up storage object.
		var storage = new ArchiveStorage();
		storage.load(localStorage.getItem(key));
		archiveList.push(storage);
	}

	return archiveList;
};

/**
 * Loads the archive from storage.
 * 
 * @param {string} [storedObject] Stringified object that was stored to be loaded.
 */
ArchiveStorage.prototype.load = function (storedObject) {
	// Fetch the object string only if necessary.
	if (storedObject === undefined)
		storedObject = localStorage.getItem(this.storageKey);

	// Ensure we actually got anything to parse.
	if (archive !== null) {
		this.archive = JSON.parse(archive);
		this.documentId = this.archive.id;
		this.storageKey = "archive_" + this.documentId;
	}
};

/**
 * Saves the archive to storage.
 */
ArchiveStorage.prototype.save = function () {
	localStorage.setItem(this.storageKey, JSON.stringify(this.archive));
};

/**
 * Gets the HTML card for displaying this archive to users.
 * 
 * @returns {Element} HTML card element to append to a container.
 */
ArchiveStorage.prototype.getHtmlCard = function () {
	var card = document.createElement("div");
	card.className = "card";

	var body = document.createElement("div");
	body.className = "card-body";

	var title = document.createElement("h5");
	title.className = "card-title";
	title.innerText = this.archive.name;
	body.appendChild(title);

	var text = document.createElement("p");
	text.className = "card-text";
	text.innerText = this.archive.description;
	body.appendChild(text);

	var link = document.createElement("a");
	link.className = "card-link";
	link.innerText = "Rev " + this.archive.revision;
	link.href = "/pick/local/" + this.archive.id;
	body.appendChild(link);

	card.appendChild(body);
	return card;
};

/**
 * Appends a descriptive card representing this archive to a container.
 * 
 * @param {string} containerId ID of the HTML container for us to append the
 * card to.
 */
ArchiveStorage.prototype.appendCardTo = function (containerId) {
	var container = document.getElementById(containerId);
	container.appendChild(this.getHtmlCard());
};
