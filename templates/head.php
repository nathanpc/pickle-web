<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . "/../config/config.php"; ?>
<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<head>
	<meta charset="utf-8" />
	<title><?= site_title((defined('PAGE_TITLE')) ? PAGE_TITLE : NULL) ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- jQuery -->
	<script src="<?= href('/lib/jquery/jquery.min.js') ?>"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?= href('/lib/bootstrap/css/bootstrap.min.css') ?>">
	<script src="<?= href('/lib/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

	<!-- Custom -->
	<link rel="stylesheet" href="<?= href('/css/custom.css') ?>">
</head>
<body>
	<!-- Navigation Bar -->
	<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="<?= SITE_URL ?>"><?= APP_NAME ?></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item active">
					<a class="nav-link" href="<?= SITE_URL ?>">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= href('/archive') ?>">Archive</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= href('/about') ?>">About</a>
				</li>
			</ul>
		</div>
	</nav>

	<!-- Main Page Contents -->
	<main class="container">
