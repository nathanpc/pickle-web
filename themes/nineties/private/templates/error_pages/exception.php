<?php require_once __DIR__ . "/../../config/functions.php"; ?>
<?php http_response_code(500); ?>
<?php $e = EXCEPTION_OBJECT; ?>

<?php require(__DIR__ . "/../head.php"); ?>

<h2>Exception</h2>
<p><?= htmlspecialchars($e->getMessage()) ?></p>

<pre><code><?= htmlspecialchars($e->getTraceAsString()) ?></code></pre>

<?php require(__DIR__ . "/../footer.php"); ?>