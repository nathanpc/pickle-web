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
	<script src="<?= href('/lib/jquery/jquery.min.js'); ?>"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?= href('/lib/bootstrap/css/bootstrap.min.css'); ?>">
	<script src="<?= href('/lib/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</head>
<body>
