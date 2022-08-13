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
	private $id;
	private $name;
	private $components;

	/**
	 * Constructs a category object.
	 * 
	 * @param string $name       Title of the category.
	 * @param array  $components Components inside this category.
	 */
	public function __construct($name = null, $components = array()) {
		$this->id = null;
		$this->name = $name;
		$this->components = $components;
	}

	/**
	 * Gets a unique ID for this component. Used for primarily for front-end
	 * IDs.
	 *
	 * @return string Unique ID for this component.
	 */
	public function get_id() {
		// Have we already generated our ID?
		if (is_null($this->id))
			$this->generate_id();

		return $this->id;
	}

	/**
	 * Generates the unique ID for this component.
	 */
	protected function generate_id() {
		// Lowercase the name and substitute all non-characters for slashes.
		$this->id = preg_replace('/[^A-Za-z0-9\-]/', '-', strtolower($this->name));
		$this->id = preg_replace('/\-{2,}/', '-', $this->id);

		// Checksum by summing up all of the character values in its components IDs.
		$checksum = 0;
		foreach ($this->components as $component) {
			$comp_id = $component->get_id();
			$len = strlen($comp_id);

			for ($i = 0; $i < $len; $i++) {
				$checksum += ord($comp_id[$i]);
			}
		}

		// Append the checksum in hexadecimal.
		$this->id = $this->id . '-' . dechex($checksum);
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
		$this->generate_id();
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
		$this->generate_id();
	}

	/**
	 * Adds a component to the components list inside of this category.
	 * 
	 * @param Component $component Component to add to the list.
	 */
	public function add_component($component) {
		array_push($this->components, $component);
		$this->generate_id();
	}
}