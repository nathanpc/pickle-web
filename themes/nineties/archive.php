<?php define('PAGE_TITLE', 'Archive'); ?>
<?php define('PAGE_SUBTITLE', 'A collection of saved pick lists'); ?>
<?php require(__DIR__ . "/private/templates/head.php"); ?>
<?php $archives = PickLE\Document::ListArchives(); ?>

<dl>
	<?php foreach ($archives as $doc) { ?>
		<dt><b><?= $doc->get_name() ?></b> (<a href="<?= href('/pick/' . $doc->get_archive_name()) ?>">Rev <?= $doc->get_revision() ?></a>)</dt>
		<dd><?= $doc->get_description() ?></dd>
	<?php } ?>
</dl>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>