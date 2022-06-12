<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php require_once __DIR__ . "/../config/config.php"; ?>
<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
	<head>
		<meta charset="utf-8" />
		<title><?= site_title((defined('PAGE_TITLE')) ? PAGE_TITLE : NULL) ?></title>
		
		<!-- jQuery -->
		<script type="text/javascript" src="<?= href('/lib/jquery-1.12.4.min.js'); ?>"></script>
	</head>
	<body>
