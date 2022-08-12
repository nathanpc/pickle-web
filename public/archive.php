<?php define('PAGE_TITLE', 'Archive'); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>
<?php $archives = PickLE\Document::ListArchives(); ?>

<h3>
	<?= PAGE_TITLE ?>
	<small class="text-muted">A collection of saved pick lists</small>
</h3>

<br>

<?php foreach ($archives as $doc) { ?>
	<div class="card">
		<div class="card-body">
			<h5 class="card-title"><?= $doc->get_name() ?></h5>
			<p class="card-text"><?= $doc->get_description() ?></p>

			<a href="<?= href('/pick/' . $doc->get_archive_name()) ?>" class="card-link">
				Rev <?= $doc->get_revision() ?>
			</a>
		</div>
	</div>

	<br>
<?php } ?>

<?php require(__DIR__ . "/../templates/footer.php"); ?>