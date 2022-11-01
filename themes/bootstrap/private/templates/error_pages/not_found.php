<?php require_once __DIR__ . "/../../../../../config/functions.php"; ?>
<?php http_response_code(404); ?>

<?php require(__DIR__ . "/../head.php"); ?>

<div class="jumbotron">
	<h1 class="display-4"><?= PAGE_TITLE ?></h1>
	<p class="lead">
		<?= ERROR_MESSAGE ?>
	</p>
</div>

<?php require(__DIR__ . "/../footer.php"); ?>