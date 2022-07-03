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
	private $categories;

	protected const ARCHIVE_DIR = __DIR__ . "/../resources/pkl/";

	/**
	 * Constructs an empty document object.
	 * 
	 * @param array $categories Categories of components to be picked.
	 */
	public function __construct($categories = array()) {
		$this->archive_name = NULL;
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

		// Open the file.
		$handle = fopen($file, "r");
		if (!$handle)
			throw new Exception("Error while trying to open file $file");

		// Go through the file line-by-line.
		$stage = "empty";
		$categories = array();
		$category = NULL;
		$component = NULL;
		while (($line = fgets($handle)) !== false) {
			// Clean up before parsing.
			$line = trim($line);

			if ($stage == "empty") {
				if (Component::IsDescriptorLine($line)) {
					// Make sure we have a category defined.
					if (is_null($category)) {
						throw new Exception("Trying to create a component without parent category");
					}
					
					// Change the stage and parse a new component.
					$component = Component::FromDescriptorLine($line);
					$stage = "refdes";
					continue;
				} else if (Category::IsCategoryLine($line)) {
					// Check if we need to commit our parsed category first.
					if ($category != NULL)
						array_push($categories, $category);

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
			}
		}

		// Make sure the last parsed category is accounted for.
		if ($category != NULL)
			array_push($categories, $category);

		// Close the file handle.
		fclose($handle);

		// Populate the categories array and return ourselves.
		$doc->set_categories($categories);
		return $doc;
	}

	/**
	 * Constructs a document object given a pick list archive name.
	 * 
	 * @param string  $name     Document name in the archives folder.
	 * @param boolean $sanitize Sanitize the name before using it.
	 */
	public static function FromArchive($name, $sanitize = true) {
		// Make sure the string is clean and safe.
		if ($sanitize)
			$name = preg_replace('/[^0-9a-zA-Z\-_]/', '', $name);

		return Document::FromFile(Document::ARCHIVE_DIR . $name . '.pkl');
	}

	/**
	 * Constructs a document object given a pick list document contents string.
	 * 
	 * @param string $name     Archive name.
	 * @param string $contents Document contents to be parsed.
	 */
	public static function FromString($name, $contents) {
		$this->archive_name = $name;
		// TODO
		throw new Exception("Not yet implemented");
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
