<?php
/**
 * Category.php
 * Pick list category abstraction class.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;
require __DIR__ . "/../vendor/autoload.php";

class Category {
	private $name;
	private $components;

	/**
	 * Constructs a category object.
	 * 
	 * @param string $name       Title of the category.
	 * @param array  $components Components inside this category.
	 */
	public function __construct($name = null, $components = array()) {
		$this->name = $name;
		$this->components = $components;
	}

	/**
	 * Gets the category title.
	 * 
	 * @return string Category title.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Sets the category title.
	 * 
	 * @param string $name Category title.
	 */
	public function set_name($name) {
		$this->name = $name;
	}

	/**
	 * Gets the list of components inside this category.
	 * 
	 * @return array List of components inside this category.
	 */
	public function get_components() {
		return $this->components;
	}

	/**
	 * Sets the list of components inside of this category.
	 * 
	 * @param array $components Components inside the category.
	 */
	public function set_components($components) {
		$this->components = $components;
	}

	/**
	 * Adds a component to the components list inside of this category.
	 * 
	 * @param Component $component Component to add to the list.
	 */
	public function add_component($component) {
		array_push($this->components, $component);
	}
}