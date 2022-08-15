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
		</div>
	</nav>

	<!-- Main Page Contents -->
	<main class="container">