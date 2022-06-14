<?php
/**
 * Component.php
 * Pick list item abstration class.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;
require __DIR__ . "/../vendor/autoload.php";

class Component {
	private $picked;
	private $name;
	private $value;
	private $description;
	private $package;
	private $refdes;

	/**
	 * Constructs a component object.
	 * 
	 * @param boolean $picked      Has this cmponent been picked already?
	 * @param string  $name        Manufacturer's part number.
	 * @param string  $value       Value of the component if applicable.
	 * @param string  $description Brief description of the component.
	 * @param string  $package     Type of package the component comes in.
	 * @param array   $refdes      Reference designators in the PCB.
	 */
	public function __construct($picked = false, $name = null, $value = null,
			$description = null, $package = null, $refdes = array()) {
		$this->picked = $picked;
		$this->name = $name;
		$this->value = $value;
		$this->description = $description;
		$this->package = $package;
		$this->refdes = $refdes;
	}

	/**
	 * Constructs a component object from a descriptor line in an pick list
	 * document file.
	 * 
	 * @param  string    $line Descriptor line to be parsed.
	 * @return Component       Pre-populated component object.
	 */
	public static function FromDescriptorLine($line) {
		// TODO
	}

	/**
	 * Parses the reference designator line of an pick list document file and
	 * populates the internal array.
	 * 
	 * @param string $line Reference designator line to be parsed.
	 */
	public function parse_refdes_line($line) {
		$this->refdes = array_filter(explode(" ", $line));
	}

	/**
	 * Checks if a given line in an pick list document is a component
	 * descriptor line.
	 * 
	 * @param  string  $line Document line to be tested.
	 * @return boolean       Is this a valid descriptor line?
	 */
	public static function IsDescriptorLine($line) {
		// Empty lines are just separators.
		if (strlen($line) == 0)
			return false;

		// If the line starts with an open bracket means we have a descriptor.
		return $line[0] == '[';
	}

	/**
	 * Gets the quantity of the component that needs to be picked based on the
	 * number of reference designators the component has.
	 * 
	 * @return int Number of components that needs to be picked.
	 */
	public function get_quantity() {
		return count($this->refdes);
	}

	/**
	 * Has this component already been picked?
	 * 
	 * @return boolean Was it picked already?
	 */
	public function is_picked() {
		return $this->picked;
	}

	/**
	 * Sets the picked property of the component.
	 * 
	 * @param boolean $picked Has this cmponent been picked already?
	 */
	public function set_picked($picked) {
		$this->picked = $picked;
	}

	/**
	 * Gets the manufacturer's part number related to this component.
	 * 
	 * @return string Manufacturer's part number.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Sets the manufacturer's part number related to this component.
	 * 
	 * @param string $name Manufacturer's part number.
	 */
	public function set_name($name) {
		$this->name = $name;
	}

	/**
	 * Does this component contain a valid value property?
	 * 
	 * @return boolean Does it have a valid value?
	 */
	public function has_value() {
		if (is_null($this->value))
			return false;

		return strlen($this->value) > 0;
	}

	/**
	 * Gets the value of the component.
	 * 
	 * @return string Value of the component.
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Sets the value of the component.
	 * 
	 * @param string $value Value of the component.
	 */
	public function set_value($value) {
		$this->value = $value;
	}

	/**
	 * Does this component contain a valid description property?
	 * 
	 * @return boolean Does it have a valid description?
	 */
	public function has_description() {
		if (is_null($this->description))
			return false;

		return strlen($this->description) > 0;
	}

	/**
	 * Gets the description of the component.
	 * 
	 * @return string Description of the component.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Sets the description of the component.
	 * 
	 * @param string $description Description of the component.
	 */
	public function set_description($description) {
		$this->description = $description;
	}

	/**
	 * Does this component contain a valid package property?
	 * 
	 * @return boolean Does it have a valid package?
	 */
	public function has_package() {
		if (is_null($this->package))
			return false;

		return strlen($this->package) > 0;
	}

	/**
	 * Gets the package of the component.
	 * 
	 * @return string Package of the component.
	 */
	public function get_package() {
		return $this->package;
	}

	/**
	 * Sets the package of the component.
	 * 
	 * @param string $package Package of the component.
	 */
	public function set_package($package) {
		$this->package = $package;
	}

	/**
	 * Gets the reference designators of the component.
	 * 
	 * @return array Reference designators of the component.
	 */
	public function get_refdes() {
		return $this->refdes;
	}

	/**
	 * Sets the reference designators of the component.
	 * 
	 * @param array $refdes Reference designators of the component.
	 */
	public function set_refdes($refdes) {
		$this->refdes = $refdes;
	}

	/**
	 * Adds a reference designator to the component list of reference
	 * designators.
	 * 
	 * @param string $refdes Reference designator to add to the list.
	 */
	public function add_refdes($refdes) {
		array_push($this->refdes, $refdes);
	}
}