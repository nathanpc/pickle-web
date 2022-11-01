<?php require_once __DIR__ . "/../../../../../config/functions.php"; ?>
<?php http_response_code(404); ?>

<?php require(__DIR__ . "/../head.php"); ?>

<h2>Not Found</h2>
<p><?= ERROR_MESSAGE ?></p>

<?php require(__DIR__ . "/../footer.php"); ?>