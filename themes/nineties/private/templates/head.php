<?php require_once __DIR__ . "/../functions.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?= site_title() ?></title>

	<!-- Styling -->
	<link rel="stylesheet" href="<?= href('/css/main.css') ?>">
</head>

<body>
	<h1>
		<?= APP_NAME ?><?php if (defined('PAGE_TITLE')) { ?>: <a href="<?= $_SERVER['REQUEST_URI'] ?>"><?= PAGE_TITLE ?></a><?php } ?>
	</h1>

	<!-- Navigation -->
	<font size="3">
		<?= nav_item('Home', '/', 'index') ?> | <?= nav_item('Submit', '/upload', 'upload') ?> | <?= nav_item('Archive', '/archive', 'archive') ?> | <?= nav_item('About', '/about', 'about') ?>
		<?php if (defined('PAGE_SUBTITLE')) { ?>
			<br>
			<i><?= PAGE_SUBTITLE ?></i>
		<?php } ?>
		<hr>
	</font>

	<!-- Main Page Contents -->
	<div id="main">