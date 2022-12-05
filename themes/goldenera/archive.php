<?php define('PAGE_TITLE', 'Archive'); ?>
<?php define('PAGE_SUBTITLE', 'A collection of saved pick lists'); ?>
<?php require(__DIR__ . "/private/templates/head.php"); ?>
<?php $archives = PickLE\Document::ListArchives(); ?>

<!-- Archives -->
<div id="archives-container">
	<?php foreach ($archives as $doc) { ?>
		<div class="archive-block">
			<div class="title"><?= $doc->get_name() ?></div>
			<div class="desc"><?= $doc->get_description() ?></div>

			<div class="revisions-list">
				<a href="<?= href('/pick/' . $doc->get_archive_name()) ?>">
					Rev <?= $doc->get_revision() ?>
				</a>
			</div>
		</div>
	<?php } ?>
</div>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>