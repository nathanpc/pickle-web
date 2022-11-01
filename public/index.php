<?php
/**
 * index.php
 * The catch all script for every request in the project.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace PickLE;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/functions.php";
require_once __DIR__ . "/../vendor/autoload.php";

// Setup our router and render the requested file.
$router = new Router($_GET["path"]);
$router->render_page();
