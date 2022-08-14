<?php define('PAGE_TITLE', 'Archive'); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>
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
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Sample Title</h5>
					<p class="card-text">Sample description here!</p>

					<a href="<?= href('/pick/test') ?>" class="card-link">
						Rev Test
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<br>

<?php require(__DIR__ . "/../templates/footer.php"); ?>