<?php require_once __DIR__ . "/../functions.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?= site_title() ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Styling -->
	<link rel="stylesheet" href="<?= href('/css/main.css') ?>">
</head>

<body>
	<!-- Navigation Bar -->
	<div id="navbar">
		<h1>Pick<span class="pickle">LE</span></h1>

		<!-- Navigation -->
		<div class="nav">
			<?= nav_item('Home', '/', 'index') ?> ‧
			<?= nav_item('Submit', '/upload', 'upload') ?> ‧
			<?= nav_item('Archive', '/archive', 'archive') ?> ‧
			<?= nav_item('About', '/about', 'about') ?>
		</div>
	</div>

	<!-- Main Page Contents -->
	<div id="main">