<?php require(__DIR__ . "/../templates/head.php"); ?>

<div class="jumbotron">
	<h1 class="display-4">Welcome to <?= APP_NAME ?></h1>

	<p class="lead">This web application allows you to work with <a href="https://github.com/nathanpc/pickle">PickLE</a> pick list documents anywhere you want, making it easy to build your next DIY project or start on that production run of your next product!</p>

	<hr class="my-4">

	<p>PickLE documents are simple, human-readable, component pick list declaration files for your projects. Gone are the days of printing out BOMs and crossing out parts you've already retrieved from your parts bins.</p>

	<a class="btn btn-primary btn-lg" href="<?= href('/about/') ?>" role="button">Learn more</a>
</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>