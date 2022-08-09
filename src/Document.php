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
	private $archive_name;
	private $properties;
	private $categories;

	protected const ARCHIVE_DIR = __DIR__ . "/../resources/pkl/";

	/**
	 * Constructs an empty document object.
	 * 
	 * @param array $properties Some descriptive information about this
	 *                          specific document.
	 * @param array $categories Categories of components to be picked.
	 */
	public function __construct($properties = array(), $categories = array()) {
		$this->archive_name = NULL;
		$this->properties = $properties;
		$this->categories = $categories;
	}

	/**
	 * Constructs a document object given a pick list document file.
	 * 
	 * @param string $file File to be parsed.
	 */
	public static function FromFile($file) {
		// Construct ourselves and set our archive name.
		$doc = new Document();
		$doc->set_archive_name(basename($file, '.pkl'));

		// Check if the file exists.
		if (!file_exists($file))
			return NULL;

		// Open the file.
		$handle = fopen($file, "r");
		if (!$handle)
			throw new \Exception("Error while trying to open file '$file'");

		// Go through the file line-by-line.
		$stage = "properties";
		$category = NULL;
		$component = NULL;
		while (($line = fgets($handle)) !== false) {
			// Clean up before parsing.
			$line = trim($line);

			if ($stage == "empty") {
				if (Component::IsDescriptorLine($line)) {
					// Make sure we have a category defined.
					if (is_null($category)) {
						throw new \Exception("Trying to create a component without parent category");
					}
					
					// Change the stage and parse a new component.
					$component = Component::FromDescriptorLine($line);
					$stage = "refdes";
					continue;
				} else if (Category::IsCategoryLine($line)) {
					// Check if we need to commit our parsed category first.
					if ($category != NULL)
						$doc->add_category($category);

					// Create the new category.
					$category = Category::FromCategoryLine($line);
					$stage = "empty";
					continue;
				} else if ($line == "") {
					// Just another empty line...
					continue;
				}
			} else if ($stage == "refdes") {
				// Looks like we've finished parsing this component.
				if ($line == "") {
					// Add component to the category.
					$category->add_component($component);
					
					// Reset everything.
					$component = NULL;
					$stage = "empty";
					continue;
				}

				// Parse the reference designators and add the component.
				$component->parse_refdes_line($line);
				$category->add_component($component);
				$stage = "empty";
				continue;
			} else if ($stage == "properties") {
				// Looks like we are in the properties header.
				if ($line == "")
					continue;

				// Have we finished parsing the properties header?
				if ($line == "---") {
					$stage = "empty";
					continue;
				}

				// Check if we've got a property.
				if (!Property::IsPropertyLine($line))
					throw new Exception("The header section of a document must only contain properties");

				// Append the property to our parsed document object.
				$doc->add_property(Property::FromPropertyLine($line));
			}
		}

		// Make sure the last parsed category is accounted for.
		if ($category != NULL)
			$doc->add_category($category);

		// Close the file handle.
		fclose($handle);

		// Return ourselves.
		return $doc;
	}

	/**
	 * Constructs a document object given a pick list archive name.
	 * 
	 * @param string  $name     Document name in the archives folder.
	 * @param boolean $sanitize Sanitize the name before using it.
	 */
	public static function FromArchive($name = NULL, $sanitize = true) {
		// Do we even have an archive to deal with?
		if (is_null($name))
			return NULL;

		// Make sure the string is clean and safe.
		if ($sanitize)
			$name = preg_replace('/[^0-9a-zA-Z\-_]/', '', $name);

		try {
			// Parse the archive.
			return Document::FromFile(Document::ARCHIVE_DIR . $name . '.pkl');
		} catch (\Exception $e) {
			return NULL;
		}
	}

	/**
	 * Constructs a document object given a pick list document contents string.
	 * 
	 * @param string $name     Archive name.
	 * @param string $contents Document contents to be parsed.
	 */
	public static function FromString($name, $contents) {
		// TODO
		//$this->archive_name = $name;
		throw new \Exception("Not yet implemented");
	}

	/**
	 * Gets the archive name of this document.
	 * 
	 * @return string Achive name of the document.
	 */
	public function get_archive_name() {
		return $this->archive_name;
	}

	/**
	 * Sets the archive name of this document.
	 * 
	 * @param string $name Achive name of the document.
	 */
	public function set_archive_name($name) {
		$this->archive_name = $name;
	}

	/**
	 * Gets the list of properties of the document.
	 * 
	 * @return array List of properties of this document.
	 */
	public function get_properties() {
		return $this->properties;
	}

	/**
	 * Sets the list of properties of the document.
	 * 
	 * @param array $properties List of properties of this document.
	 */
	public function set_properties($properties) {
		$this->properties = $properties;
	}

	/**
	 * Adds a property to the properties list of this document.
	 * 
	 * @param Property $property Property to add to the list.
	 */
	public function add_property($property) {
		array_push($this->properties, $property);
	}

	/**
	 * Checks if the document has a property with a specific name.
	 * 
	 * @param  string  $name Name of the property to search for.
	 * @return boolean       Do we have said property?
	 */
	public function has_property($name) {
		// Go through our properties trying to find this one.
		foreach ($this->properties as $property) {
			// Have we found it?
			if ($property->get_name() == $name)
				return true;
		}

		return false;
	}

	/**
	 * Gets a specific property from the document.
	 * 
	 * @param  string $name Property name to get.
	 * @return string       Requested property value or an empty string if the
	 *                      requested property didn't exist.
	 */
	public function get_property($name) {
		// Go through our properties trying to find this one.
		foreach ($this->properties as $property) {
			// Have we found it?
			if ($property->get_name() == $name)
				return $property->get_value();
		}
		
		// Looks like we were unable to find anything.
		return "";
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
	public function add_category($category) {
		array_push($this->categories, $category);
	}
}
