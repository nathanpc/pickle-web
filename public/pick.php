<?php require(__DIR__ . "/../templates/head.php"); ?>

<h3>
	Pick List
	<small class="text-muted">Everything that we need to pick up</small>
</h3>

<?php $picklist = PickLE\Document::FromArchive($_GET["archive"]); ?>

<?php foreach ($picklist->get_categories() as $category) { ?>
	<h3><?= $category->get_name() ?></h3>

	<div class="table-responsive-lg">
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col" class="text-center">Picked</th>
					<th scope="col" class="text-center">Quantity</th>
					<th scope="col">Part #</th>
					<th scope="col">Value</th>
					<th scope="col">Reference Designators</th>
					<th scope="col">Description</th>
					<th scope="col">Package</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($category->get_components() as $component) { ?>
					<tr>
						<td class="text-center"><input type="checkbox"></td>
						<td class="text-center"><?= $component->get_quantity() ?></td>
						<th scope="row"><?= $component->get_name() ?></th>
						<td><?= $component->get_value() ?></td>
						<td><?= implode(' ', $component->get_refdes()) ?></td>
						<td><?= $component->get_description() ?></td>
						<td><?= $component->get_package() ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>

<?php require(__DIR__ . "/../templates/footer.php"); ?>