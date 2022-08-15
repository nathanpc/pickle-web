<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . "/../config/config.php"; ?>
<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>

<head>
	<meta charset="utf-8" />
	<title><?= site_title() ?></title>
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
				<?= nav_item('Submit', '/upload', 'upload') ?>
				<?= nav_item('Archive', '/archive', 'archive') ?>
				<?= nav_item('About', '/about', 'about') ?>
			</ul>

			<?php if (is_parent_page('pick') && isset($picklist)) { ?>
				<!--<form class="form-inline my-2 my-lg-0" method="GET" action="/pick">
					<input type="hidden" name="archive" value="<?= $picklist->get_archive_name() ?>">
					<input class="form-control mr-2" id="lotsize" type="number" name="lotsize" placeholder="Lot Size" aria-label="Lot Size" min="1" <?= ($lot_size > 1) ? 'value="' . $lot_size . '"' : '' ?>>
					<button class="btn btn-outline-light" type="submit">Apply</button>
				</form>-->

				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
							Actions
						</a>
						<div class="dropdown-menu">
							<button class="dropdown-item" type="button" data-toggle="modal" data-target="#source-modal">
								View Source
							</button>
							<button class="dropdown-item" type="button">
								Save to Local Storage
							</button>
							<button class="dropdown-item" type="button">
								Clear Picks
							</button>
							<button class="dropdown-item" type="button">
								Clear Placements
							</button>
						</div>
					</li>
				</ul>
			<?php } ?>
		</div>
	</nav>

	<!-- Main Page Contents -->
	<main class="container">