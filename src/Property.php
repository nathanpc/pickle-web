<?php
/**
 * Property.php
 * Pick list property abstraction class.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;
require __DIR__ . "/../vendor/autoload.php";

class Property {
	private $name;
	private $value;

	/**
	 * Constructs a property object.
	 * 
	 * @param string $name  Name of the property.
	 * @param string $value Value of the property.
	 */
	public function __construct($name = null, $value = null) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Gets the property name.
	 * 
	 * @return string Property name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Sets the property name.
	 * 
	 * @param string $name Property name.
	 */
	public function set_name($name) {
		$this->name = str_replace(' ', '-', $name);
	}

	/**
	 * Gets the property value.
	 * 
	 * @return string Property value.
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Sets the property value.
	 * 
	 * @param string $value Property value.
	 */
	public function set_value($value) {
		$this->value = $value;
	}
}