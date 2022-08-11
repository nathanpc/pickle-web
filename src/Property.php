<?php
/**
 * Property.php
 * Pick list property abstration class.
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
	 * Constructs a property object from a property header line in an pick list
	 * document file.
	 * 
	 * @param  string   $line Property line to be parsed.
	 * @return Property       Pre-populated property object.
	 */
	public static function FromPropertyLine($line) {
		$matches = null;
		if (!preg_match('/(?<name>[A-Za-z0-9 \-]+): (?<value>.+)/', $line, $matches))
			return null;

		return new Property($matches['name'], $matches['value']);
	}

	/**
	 * Checks if a given line in an pick list document is a property line.
	 * 
	 * @param  string  $line Document line to be tested.
	 * @return boolean       Is this a valid property line?
	 */
	public static function IsPropertyLine($line) {
		// Empty lines are just separators.
		if (strlen($line) == 0)
			return false;

		// Do we even have a colon-space in our line?
		if (!str_contains($line, ": "))
			return false;

		// Do we have the colon somewhere in the middle of the string?
		return !str_ends_with($line, ": ");
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