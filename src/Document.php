<?php
/**
 * Document.php
 * Pick list document abstration class.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;
require __DIR__ . "/../vendor/autoload.php";

class Document {
	private $categories;

	/**
	 * Constructs an empty document object.
	 * 
	 * @param array $categories Categories of components to be picked.
	 */
	public function __construct($categories = array()) {
		$this->categories = $categories;
	}

	/**
	 * Constructs a document object given a pick list document file.
	 * 
	 * @param string $file File to be parsed.
	 */
	public static function FromFile($file) {
		// TODO
	}

	/**
	 * Constructs a document object given a pick list document contents string.
	 * 
	 * @param string $contents Document contents to be parsed.
	 */
	public static function FromString($contents) {
		// TODO
	}

	/**
	 * Gets the list of categories of components to be picked.
	 * 
	 * @return array List of categories inside this document.
	 */
	public function get_categories() {
		return $this->categories;
	}

	/**
	 * Sets the list of categories of components to be picked.
	 * 
	 * @param array $categories Categories inside this document.
	 */
	public function set_categories($categories) {
		$this->categories = $categories;
	}

	/**
	 * Adds a category to the categories list inside of this document.
	 * 
	 * @param Category $category Category to add to the list.
	 */
	public function add_component($category) {
		array_push($this->categories, $category);
	}
}
