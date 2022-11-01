<?php $picklist = NULL; ?>
<?php $lot_size = intval(urlparam('lotsize', 1)); ?>
<?php
try {
	// Try to get and parse the requested pick list.
	$picklist = get_picklist_from_req();

	// Check if the requested archive wasn't found.
	if (is_null($picklist)) {
		$router->render_error_page('not_found', array(
			'PAGE_TITLE' => 'Archive Not Found',
			'ERROR_MESSAGE' => "The archive that you've requested was not " .
				"found. Maybe check out the <a href=" . href('/archive') .
				">archives page</a>?"
		));

		return;
	}
} catch (Exception $e) {
	// Parsing error.
	$router->render_error_page('exception', array(
		'PAGE_TITLE' => 'Parsing Error',
		'EXCEPTION_OBJECT' => $e
	));

	return;
}
?>

<?php define('PAGE_TITLE', 'Pick List'); ?>
<?php require(__DIR__ . "/private/templates/head.php"); ?>

<!-- Document Information -->
<h3>
	<?= $picklist->get_name() ?>
	<small class="text-muted">Rev <?= $picklist->get_revision() ?></small>
</h3>
<p class="lead"><?= $picklist->get_description() ?></p>

<div id="more-info" class="d-none">
	<!-- Optional Properties -->
	<dl id=" doc-properties" class="row mb-0">
		<?php foreach ($picklist->get_properties() as $property) { ?>
			<dt class="col-sm-2"><?= $property->get_name() ?></dt>
			<dd class="col-sm-10"><?= auto_link($property->get_value()) ?></dd>
		<?php } ?>
	</dl>

	<br>

	<!-- Actions Button Group -->
	<div id="actions-btn-group" class="d-flex justify-content-center">
		<div class=" btn-toolbar mb-4" role="toolbar" aria-label="Toolbar with button groups">
			<!-- Source Actions -->
			<div class="btn-group mr-5" role="group" aria-label="Source actions button group">
				<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#source-modal">
					Edit Source
				</button>
			</div>

			<!-- Storage Actions -->
			<div class="btn-group mr-5" role="group" aria-label="Storage actions button group">
				<button type="button" class="btn btn-secondary" onclick="archive.save(); archive.browsePickPage();">
					Save Locally
				</button>
				<button type=" button" class="btn btn-secondary" onclick="archive.delete(true)">
					Delete
				</button>
			</div>

			<!-- Clearing Actions -->
			<div class="btn-group mr-5" role="group" aria-label="Clearing actions button group">
				<button type="button" class="btn btn-secondary" onclick="pickState.clearPicks(true)">
					Clear Picks
				</button>
				<button type=" button" class="btn btn-secondary" onclick="pickState.clearPlacements(true)">
					Clear Placements
				</button>
			</div>

			<!-- Lot Size Selector -->
			<form class="form-inline my-2 my-lg-0" method="GET" action="/pick">
				<input type="hidden" name="archive" value="<?= $picklist->get_archive_name() ?>">

				<div class="input-group">
					<input type="number" id="lotsize" class="form-control" name="lotsize" placeholder="Lot Size" aria-label="Lot Size" min="1" <?= ($lot_size > 1) ? 'value="' . $lot_size . '"' : '' ?>>

					<div class=" input-group-append">
						<button class="btn btn-secondary" type="submit">Apply</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="container text-center">
	<button type="button" class="btn btn-link" onclick="toggleElementVisibility('#more-info', this)">More Information</button>
</div>

<br>

<!-- Actual Pick List -->
<?php foreach ($picklist->get_categories() as $category) { ?>
	<div id="<?= $category->get_id() ?>">
		<h3><?= $category->get_name() ?></h3>

		<div class="table-responsive-lg">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th scope="col" class="col-1 text-center">Picked</th>
						<th scope="col" class="col-1 text-center">Quantity</th>
						<th scope="col" class="col-2">Part #</th>
						<th scope="col" class="col-1 text-center">Value</th>
						<th scope="col" class="col-4">Reference Designators</th>
						<th scope="col" class="col-2">Description</th>
						<th scope="col" class="col-1">Package</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($category->get_components() as $component) { ?>
						<?php $chk_id = $component->get_id() . '-picked'; ?>

						<tr id="<?= $component->get_id() ?>" onclick="pickState.handleToggleComponentPicked('<?= $component->get_id() ?>', event)" onmousedown="preventDblClickHighlight(event)">
							<td class="col-1 text-center"><input id="<?= $chk_id ?>" class="chk-picked" type="checkbox" onclick="pickState.handleToggleComponentPicked('<?= $component->get_id() ?>', event)"></td>
							<td class="col-1 text-center"><?= $component->get_quantity() * $lot_size ?></td>
							<th scope="row" class="col-2"><?= $component->get_name() ?></th>
							<td class="col-1 text-center"><?= $component->get_value() ?></td>
							<td class="col-4">
								<?php foreach ($component->get_refdes() as $refdes) { ?>
									<?php $refdes_id = $component->get_id() . '-refdes-' . strtolower($refdes); ?>
									<span id="<?= $refdes_id ?>" class="refdes" onclick="pickState.handleToggleRefDesPicked('<?= $component->get_id() ?>', '<?= $refdes ?>', event)" onmousedown="preventDblClickHighlight(event)">
										<?= $refdes ?>
									</span>
								<?php } ?>
							</td>
							<td class="col-2"><?= $component->get_description() ?></td>
							<td class="col-1"><?= $component->get_package() ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<br>
<?php } ?>

<!-- Source Code Modal -->
<div class="modal fade" id="source-modal" tabindex="-1" aria-labelledby="source-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<form method="POST" action="<?= href('/pick') ?>" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="source-modal-label">PickLE Document Source</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Archive Source Code Text Area -->
					<div class="mb-3">
						<textarea class="form-control text-monospace" id="archive-text" name="archive-text" rows="20" placeholder="Paste your PickLE archive contents here..." required><?= $picklist->get_source_code() ?></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="<?= href('/js/storage/picklist.js') ?>"></script>
<script src="<?= href('/js/storage/archive.js') ?>"></script>
<script>
	// Source code text area.
	var sourceBox = document.getElementById("archive-text");

	// Handle the state storage.
	var pickState = new PickListStorage("<?= $picklist->get_id() ?>");
	pickState.load();
	pickState.apply();

	// Handle local archive storage.
	var archive = new ArchiveStorage("<?= $picklist->get_id() ?>");
	if (archive.load() === null) {
		archive.archive.name = <?= json_encode($picklist->get_name(), JSON_FORCE_OBJECT) ?>;
		archive.archive.description = <?= json_encode($picklist->get_description(), JSON_FORCE_OBJECT) ?>;
		archive.archive.revision = <?= json_encode($picklist->get_revision(), JSON_FORCE_OBJECT) ?>;
		archive.archive.file = sourceBox.value;
	}
</script>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>