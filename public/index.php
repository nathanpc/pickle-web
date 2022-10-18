<?php require_once __DIR__ . "/../config/config.php"; ?>
<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<?php

// Get our requested file path.
$file_path = get_theme_path() . "/" . $_GET["path"];

// Check if the requested file exists.
if (!file_exists($file_path)) {
	http_response_code(404);
	header("Content-Type: text/plain");
	echo "Couldn't find '$file_path'\n";
	die();
}

// Reply with the correct MIME type.
$file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
if ($file_ext != "php") {
	$mime = new \Mimey\MimeTypes;
	header("Content-Type: " . $mime->getMimeType($file_ext));
}

// Render the requested file.
include $file_path;

?>