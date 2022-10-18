<?php define('PAGE_TITLE', 'Archive'); ?>
<?php require(__DIR__ . "/private/templates/head.php"); ?>
<?php $archives = PickLE\Document::ListArchives(); ?>

<h3>
	<?= PAGE_TITLE ?>
	<small class="text-muted">A collection of saved pick lists</small>
</h3>

<br>

<!-- Storage Selection -->
<div class="card">
	<!-- Card Tab Bar -->
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="community-tab" data-toggle="tab" data-target="#community" type="button" role="tab" aria-controls="community" aria-selected="true">
					Community
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="user-tab" data-toggle="tab" data-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">
					Your Submissions
				</button>
			</li>
		</ul>
	</div>

	<!-- Tabbed Contents -->
	<div class="card-body tab-content">
		<!-- Community Tab -->
		<div class="tab-pane fade show active" id="community" role="tabpanel" aria-labelledby="community-tab">
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

				<?php if (next($archives) !== false) { ?>
					<br>
				<?php } ?>
			<?php } ?>
		</div>

		<!-- User Submissions Tab -->
		<div class="tab-pane fade" id="user" role="tabpanel" aria-labelledby="user-tab">
			<div class="jumbotron">
				<h1 class="display-4">Nothing to see here</h1>
				<p class="lead">
					Looks like you haven't uploaded any pick lists yet. You can
					do so by going to the <a href="<?= href('/upload') ?>">Upload</a>
					page and submitting a file or copy and pasting the contents
					of one.
				</p>
				<p class="lead">
					Your submissions are stored on your computer using
					<a href="https://developer.mozilla.org/en-US/docs/Web/API/Storage">localStorage</a>
					and will never be stored in our servers. This also means that
					if you want to have access to your pick lists in another
					computer you'll have to re-upload it there.
				</p>
			</div>
		</div>
	</div>
</div>

<br>

<script src="<?= href('/js/storage/archive.js') ?>"></script>
<script>
	// Load up the local archives list.
	var localArchives = ArchiveStorage.list();
	var container = document.getElementById("user");

	// Clear the user submissions tab if we have anything to put there.
	if (localArchives.length > 0)
		container.innerHTML = "";

	// Populate the user submissions tab.
	for (var i = 0; i < localArchives.length; i++) {
		container.appendChild(localArchives[i].getHtmlCard());
	}
</script>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>