<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<?php $picklist = NULL; ?>
<?php $lot_size = intval(urlparam('lotsize', 1)); ?>
<?php
try {
	// Try to get and parse the requested pick list.
	$picklist = get_picklist_from_req();

	// Check if the requested archive wasn't found.
	if (is_null($picklist)) {
		return render_error_page('not_found', array(
			'PAGE_TITLE' => 'Archive Not Found',
			'ERROR_MESSAGE' => "The archive that you've requested was not " .
				"found. Maybe check out the <a href=" . href('/archive') .
				">archives page</a>?"
		));
	}
} catch (Exception $e) {
	// Parsing error.
	return render_error_page('exception', array(
		'PAGE_TITLE' => 'Parsing Error',
		'EXCEPTION_OBJECT' => $e
	));
}
?>

<?php define('PAGE_TITLE', 'Pick List'); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>

<!-- Document Information -->
<h3>
	<?= $picklist->get_name() ?>
	<small class="text-muted">Rev <?= $picklist->get_revision() ?></small>
</h3>
<p class="lead"><?= $picklist->get_description() ?></p>

<!-- Optional Properties -->
<dl id="doc-properties" class="row mb-0 d-none">
	<?php foreach ($picklist->get_properties() as $property) { ?>
		<dt class="col-sm-2"><?= $property->get_name() ?></dt>
		<dd class="col-sm-10"><?= auto_link($property->get_value()) ?></dd>
	<?php } ?>
</dl>
<div class="container text-center">
	<button type="button" class="btn btn-link" onclick="toggleElementVisibility('#doc-properties', this)">More Information</button>
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

						<tr id="<?= $component->get_id() ?>" onclick="storage.handleToggleComponentPicked('<?= $component->get_id() ?>', event)" onmousedown="preventDblClickHighlight(event)">
							<td class="col-1 text-center"><input id="<?= $chk_id ?>" class="chk-picked" type="checkbox" onclick="storage.handleToggleComponentPicked('<?= $component->get_id() ?>', event)"></td>
							<td class="col-1 text-center"><?= $component->get_quantity() * $lot_size ?></td>
							<th scope="row" class="col-2"><?= $component->get_name() ?></th>
							<td class="col-1 text-center"><?= $component->get_value() ?></td>
							<td class="col-4">
								<?php foreach ($component->get_refdes() as $refdes) { ?>
									<?php $refdes_id = $component->get_id() . '-refdes-' . strtolower($refdes); ?>
									<span id="<?= $refdes_id ?>" class="refdes" onclick="storage.handleToggleRefDesPicked('<?= $component->get_id() ?>', '<?= $refdes ?>', event)" onmousedown="preventDblClickHighlight(event)">
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
						<textarea class="form-control text-monospace" id="archive-text"
							name="archive-text" rows="20" placeholder="Paste your PickLE archive contents here..."
							required><?= $picklist->get_source_code() ?></textarea>
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
<script>
	// Handle the state storage.
	var storage = new PickListStorage("<?= $picklist->get_id() ?>");
	storage.load();
	storage.apply();
</script>

<?php require(__DIR__ . "/../templates/footer.php"); ?>