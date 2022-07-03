<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . "/../config/config.php"; ?>
<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<?php
/**
 * Generates a Bootstrap Navbar item.
 * 
 * @param  string $label    Label of the item.
 * @param  string $href     Relative URL this item points to or a full URL.
 * @param  string $pagename Destination page script name without the extension.
 * @return string           Fully-populated Bootstrap navbar item.
 */
function nav_item($label, $href, $pagename) {
	// Are we the current page?
	$current = is_parent_page($pagename);

	// Make sure we deal with relative URLs.
	if ($href[0] == '/')
		$href = href($href);

	// Build up some HTML.
	return '<li class="nav-item' . (($current) ? ' active' : '') . '"><a class="nav-link" href="' . $href . '">' . $label . (($current) ? ' <span class="sr-only">(current)</span>' : '') . '</a></li>';
}
?>
<head>
	<meta charset="utf-8" />
	<title><?= site_title((defined('PAGE_TITLE')) ? PAGE_TITLE : NULL) ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- jQuery -->
	<script src="<?= href('/lib/jquery/jquery.min.js') ?>"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?= href('/lib/bootstrap/css/bootstrap.min.css') ?>">
	<script src="<?= href('/lib/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

	<!-- Confetti -->
	<script src="<?= href('/lib/canvas-confetti/confetti.browser.min.js') ?>"></script>

	<!-- Custom -->
	<link rel="stylesheet" href="<?= href('/css/custom.css') ?>">
	<script src="<?= href('/js/functions.js') ?>"></script>
</head>
<body>
	<!-- Navigation Bar -->
	<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="<?= href('/') ?>"><?= APP_NAME ?></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav mr-auto">
				<?= nav_item('Home', '/', 'index') ?>
				<?= nav_item('Archive', '/archive', 'archive') ?>
				<?= nav_item('About', '/about', 'about') ?>
			</ul>

			<?php if (is_parent_page('pick')) { ?>
				<form class="form-inline my-2 my-lg-0" method="GET" action="<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
					<input type="hidden" name="archive" value="<?= $picklist->get_archive_name() ?>">
					<input class="form-control mr-2" id="lotsize" type="number" name="lotsize" placeholder="Lot Size" aria-label="Lot Size" min="1" <?= ($lot_size > 1) ? 'value="' . $lot_size . '"' : '' ?>>
					<button class="btn btn-outline-light" type="submit">Apply</button>
				</form>
			<?php } ?>
		</div>
	</nav>

	<!-- Main Page Contents -->
	<main class="container">
