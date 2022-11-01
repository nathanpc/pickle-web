<?php require_once __DIR__ . "/../../../../../config/functions.php"; ?>
<?php http_response_code(500); ?>
<?php $e = EXCEPTION_OBJECT; ?>

<?php require(__DIR__ . "/../head.php"); ?>

<div class="jumbotron">
	<h1 class="display-4"><?= PAGE_TITLE ?></h1>
	<p class="lead">
		<?= htmlspecialchars($e->getMessage()) ?>
	</p>

	<br>

	<pre><code><?= htmlspecialchars($e->getTraceAsString()) ?></code></pre>
</div>

<?php require(__DIR__ . "/../footer.php"); ?>