<?php
/**
 * Document.php
 * Pick list document abstraction class.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;
require __DIR__ . "/../vendor/autoload.php";

class Document {
	private $id;
	private $archive_name;
	private $title;
	private $revision;
	private $description;
	private $properties;
	private $categories;
	private $source;

	// Constants
	protected const ARCHIVE_DIR = __DIR__ . "/../resources/pkl/";
	protected const ARCHIVE_EXT = 'pkl';

	/**
	 * Constructs an empty document object.
	 * 
	 * @param string $title       Document title property.
	 * @param string $revision    Document revision property.
	 * @param string $description Document description property.
	 * @param array  $properties  Some descriptive information about this
	 *                            specific document.
	 * @param array  $categories  Categories of components to be picked.
	 */
	public function __construct($title = NULL, $revision = NULL,
			$description = NULL, $properties = array(), $categories = array()) {
		$this->id = NULL;
		$this->title = $title;
		$this->revision = $revision;
		$this->description = $description;
		$this->archive_name = NULL;
		$this->properties = $properties;
		$this->categories = $categories;
		$this->source = NULL;
	}

	/**
	 * Constructs a document object given a pick list document file.
	 * 
	 * @param string $file File to be parsed.
	 * @param string $name Archive name to be used. If one isn't supplied we'll
	 *                     construct a slug from the archive properties.
	 */
	public static function FromFile($file, $name = NULL) {
		// Construct ourselves and set our archive name.
		$doc = new Document();
		if (!is_null($name))
			$doc->set_archive_name($name);

		// Check if the file exists.
		if (!file_exists($file))
			return NULL;

		// Open the file.
		$handle = fopen($file, "r");
		if (!$handle)
			throw new \Exception("Error while trying to open file '$file'");

		// Parse the string.
		$contents = fread($handle, filesize($file));
		$doc->parse($contents);

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
			return self::FromFile(self::get_archive_file_path($name), $name);
		} catch (\Exception $e) {
			return NULL;
		}
	}

	/**
	 * Constructs a document object given a pick list document contents string.
	 * 
	 * @param string $contents Document contents to be parsed.
	 * @param string $name     Archive name to be used. If one isn't supplied we'll
	 *                         construct a slug from the archive properties.
	 */
	public static function FromString($contents, $name = NULL) {
		// Construct ourselves and set our archive name.
		$doc = new Document();
		if (!is_null($name))
			$doc->set_archive_name($name);

		// Parse the string.
		$doc->parse($contents);

		// Return ourselves.
		return $doc;
	}

	/**
	 * Gets a list of the available archives in the resources folder.
	 *
	 * @return array List of Document objects of every archive in the resources
	 *               folder.
	 */
	public static function ListArchives() {
		$arr = array();

		// Go through files in the archives directory.
		foreach (scandir(self::ARCHIVE_DIR) as $file) {
			// Filter out any file that isn't a PickLE archive.
			if (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), self::ARCHIVE_EXT) != 0)
				continue;

			// Push the document into the array.
			array_push($arr,
				self::FromArchive(basename($file, '.' . self::ARCHIVE_EXT), false));
		}

		return $arr;
	}

	/**
	 * Saves the document to the archives folder.
	 */
	public function save() {
		// Create an archive file path.
		$filename = self::get_archive_file_path($this->get_archive_name());

		// TODO: Use PickLE Perl server to convert our object from JSON into a
		//       proper PickLE document.

		// Save contents to the file.
		file_put_contents($filename, $this->get_source_code());
	}

	/**
	 * Parses the contents of a document using the parsing microservice.
	 *
	 * @param string $contents Contents of a PickLE document.
	 */
	protected function parse($contents) {
		// Save the contents.
		$this->source = $contents;

		// Setup the request.
		$opts = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-Type: text/plain',
				'content' => $contents
			)
		);
		$context = stream_context_create($opts);

		// Fetch the parsed results from the microservice.
		$response = file_get_contents(PICKLE_API_URL . '/export/json', false, $context);
		if ($response === false)
			throw new \Exception('An error occurred while parsing using the microservice');

		// Decode the JSON object.
		$json = json_decode($response, true);

		// Populate ourselves.
		$this->title = $json['name'];
		$this->revision = $json['revision'];
		$this->description = $json['description'];

		// Populate properties.
		$this->properties = array();
		foreach ($json['properties'] as $property) {
			$this->add_property(new Property($property['name'], $property['value']));
		}

		// Populates the categories.
		$this->categories = array();
		foreach ($json['categories'] as $cat) {
			$category = new Category($cat['name']);

			foreach ($cat['components'] as $comp) {
				$category->add_component(new Component(
					$comp['picked'],
					$comp['name'],
					$comp['value'],
					$comp['description'],
					$comp['package'],
					$comp['refdes']
				));
			}

			$this->add_category($category);
		}
	}

	/**
	 * Gets the unique ID of this document. Used primarily for front-end IDs.
	 * 
	 * @return string Unique ID of this document.
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
		// Checksum by summing up all of the character values in its component IDs.
		$checksum = 0;
		foreach ($this->categories as $category) {
			$cat_id = $category->get_id();
			$len = strlen($cat_id);

			for ($i = 0; $i < $len; $i++) {
				$checksum += ord($cat_id[$i]);
			}
		}

		// Append the checksum in hexadecimal.
		$this->id = $this->get_archive_name() . '-' . dechex($checksum);
	}

	/**
	 * Gets the name of the document.
	 *
	 * @return string Name of the document.
	 */
	public function get_name() {
		return $this->title;
	}

	/**
	 * Sets the name of the document.
	 *
	 * @param string $name Name of the document.
	 */
	public function set_name($name) {
		$this->title = $name;
		if (!$this->generate_archive_name_slug())
			$this->generate_id();
	}

	/**
	 * Gets the revision of the document.
	 *
	 * @return string Revision of the document.
	 */
	public function get_revision() {
		return $this->revision;
	}

	/**
	 * Sets the revision of the document.
	 *
	 * @param string $revision Revision of the document.
	 */
	public function set_revision($revision) {
		$this->revision = $revision;
		if (!$this->generate_archive_name_slug())
			$this->generate_id();
	}

	/**
	 * Gets the description of the document.
	 *
	 * @return string Description of the document.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Sets the description of the document.
	 *
	 * @param string $description Description of the document.
	 */
	public function set_description($description) {
		$this->description = $description;
		$this->generate_id();
	}

	/**
	 * Generates an archive name slug based on the archive properties.
	 *
	 * @return boolean True if we had to generate a slug, false otherwise.
	 */
	protected function generate_archive_name_slug() {
		// Do we even have to generate it?
		if (!is_null($this->get_archive_name(false)))
			return false;

		// Only allow letters and numbers.
		$slug = preg_replace('/[^A-Za-z0-9\-]/', '-',
			$this->get_name() . '-' . $this->get_revision());

		// Remove duplicate slashes and make it lowercase.
		$slug = strtolower(preg_replace('/\-{2,}/', '-', $slug));

		// Set the new archive name.
		$this->set_archive_name($slug);
		return true;
	}

	/**
	 * Gets the archive name of this document.
	 * 
	 * @param  boolean $auto_gen Automatically generate it if one doesn't exist.
	 * @return string            Archive name of the document.
	 */
	public function get_archive_name($auto_gen = true) {
		// Have we already generated our archive name?
		if ($auto_gen && is_null($this->archive_name))
			$this->generate_archive_name_slug();

		return $this->archive_name;
	}

	/**
	 * Sets the archive name of this document.
	 * 
	 * @param string $name Archive name of the document.
	 */
	public function set_archive_name($name) {
		$this->archive_name = preg_replace('/[^A-Za-z0-9\-_]/', "", $name);
		$this->generate_id();
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
		$this->generate_id();
	}

	/**
	 * Adds a category to the categories list inside of this document.
	 * 
	 * @param Category $category Category to add to the list.
	 */
	public function add_category($category) {
		array_push($this->categories, $category);
		$this->generate_id();
	}

	/**
	 * Gets the source code for this document.
	 *
	 * @return string Source code of the document.
	 */
	public function get_source_code() {
		return $this->source;
	}

	/**
	 * Gets the pick page URL for this archive.
	 *
	 * @return string Pick page URL for this archive.
	 */
	public function get_pick_url() {
		return href('/pick/' . $this->get_archive_name());
	}

	/**
	 * Gets the full path to an archive document given a simple name slug.
	 *
	 * @param string $name Archive name.
	 * @return string Full path to the archive document file.
	 */
	protected static function get_archive_file_path($name) {
		return self::ARCHIVE_DIR . $name . '.' . self::ARCHIVE_EXT;
	}
}
