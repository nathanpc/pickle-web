<?php require_once __DIR__ . "/../config/functions.php"; ?>
<?php require_once __DIR__ . "/../vendor/autoload.php"; ?>
<?php $picklist = PickLE\Document::FromArchive($_GET["archive"]); ?>
<?php $lot_size = intval(urlparam('lotsize', 1)); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>

<!-- Document Information -->
<h3>
	<?= $picklist->get_property('Name') ?>
	<small class="text-muted">Rev <?= $picklist->get_property('Revision') ?></small>
</h3>
<p class="lead"><?= $picklist->get_property('Description') ?></p>

<!-- Optional Properties -->
<dl id="doc-properties" class="row mb-0 d-none">
	<?php foreach ($picklist->get_properties() as $property) { ?>
		<?php if (($property->get_name() != 'Name') && ($property->get_name() != 'Description') && ($property->get_name() != 'Revision')) { ?>
			<dt class="col-sm-2"><?= $property->get_pretty_name() ?></dt>
			<dd class="col-sm-10"><?= $property->get_value() ?></dd>
		<?php } ?>
	<?php } ?>
</dl>
<div class="container text-center">
	<button type="button" class="btn btn-link" onclick="toggleElementVisibility('#doc-properties', this)">More Information</button>
</div>
<br>

<!-- Actual Pick List -->
<?php $index = 0; ?>
<?php foreach ($picklist->get_categories() as $category) { ?>
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
					<?php $chk_id = 'chk-comp-' . $index++; ?>

					<tr onclick="toggleCheckboxCheck(event, '<?= $chk_id ?>')" onmousedown="preventDblClickHighlight(event)">
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
<?php } ?>

<?php require(__DIR__ . "/../templates/footer.php"); ?>