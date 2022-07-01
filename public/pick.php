<?php require(__DIR__ . "/../templates/head.php"); ?>

<h3>
	Pick List
	<small class="text-muted">Everything that we need to pick up</small>
</h3>

<?php $picklist = PickLE\Document::FromArchive($_GET["archive"]); ?>
<?php $lot_size = intval(urlparam('lot', 1)); ?>

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

					<tr onclick="toggle_checkbox('<?= $chk_id ?>')">
						<td class="col-1 text-center"><input id="<?= $chk_id ?>" type="checkbox"></td>
						<td class="col-1 text-center"><?= $component->get_quantity() * $lot_size ?></td>
						<th scope="row" class="col-2"><?= $component->get_name() ?></th>
						<td class="col-1 text-center"><?= $component->get_value() ?></td>
						<td class="col-4"><?= implode(' ', $component->get_refdes()) ?></td>
						<td class="col-2"><?= $component->get_description() ?></td>
						<td class="col-1"><?= $component->get_package() ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>

<?php require(__DIR__ . "/../templates/footer.php"); ?>