<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<?php $picklist = NULL; ?>
<?php $lot_size = intval(urlparam('lotsize', 1)); ?>
<?php define('PAGE_TITLE', 'Pick List'); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>

<?php try {
	$picklist = get_picklist_from_req();
} catch (Exception $e) { ?>
	<!-- Parsing Error -->
	<?php http_response_code(500); ?>
	<div class="jumbotron">
		<h1 class="display-4">Parsing Error</h1>
		<p class="lead">
			<?= htmlspecialchars($e->getMessage()) ?>
		</p>

		<br>

		<pre><code><?= htmlspecialchars($e->getTraceAsString()) ?></code></pre>
	</div>

	<?php require(__DIR__ . "/../templates/footer.php"); ?>
	<?php return; ?>
<?php } ?>

<?php if (is_null($picklist)) { ?>
	<!-- Archive Not Found -->
	<?php http_response_code(404); ?>
	<div class="jumbotron">
		<h1 class="display-4">Archive not Found</h1>
		<p class="lead">
			The archive that you've requested was not found. Maybe check out the
			<a href="<?= href('archive') ?>">archives page</a>?
		</p>
	</div>

	<?php require(__DIR__ . "/../templates/footer.php"); ?>
	<?php return; ?>
<?php } ?>

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
						<?php $chk_id = 'chk-' . $component->get_id(); ?>

						<tr id="<?= $component->get_id() ?>" onclick="toggleCheckboxCheck(event, '<?= $chk_id ?>')" onmousedown="preventDblClickHighlight(event)">
							<td class="col-1 text-center"><input id="<?= $chk_id ?>" class="chk-picked" type="checkbox" onclick="toggleCheckboxCheck(event)"></td>
							<td class="col-1 text-center"><?= $component->get_quantity() * $lot_size ?></td>
							<th scope="row" class="col-2"><?= $component->get_name() ?></th>
							<td class="col-1 text-center"><?= $component->get_value() ?></td>
							<td class="col-4">
								<?php foreach ($component->get_refdes() as $refdes) { ?>
									<span class="refdes" onclick="toggleStrikethrough(this, event)" onmousedown="preventDblClickHighlight(event)"><?= $refdes ?></span>
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

<script src="<?= href('/js/storage/picklist.js') ?>"></script>
<script>
	var storage = new PickListStorage("<?= $picklist->get_id() ?>");
	storage.load();
	storage.apply();
</script>

<?php require(__DIR__ . "/../templates/footer.php"); ?>