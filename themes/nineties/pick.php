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
<h2>
	<?= $picklist->get_name() ?>
	<font size="-1">(Rev <?= $picklist->get_revision() ?>)</font>
</h2>
<p><?= $picklist->get_description() ?></p>

<!-- Optional Properties -->
<dl>
	<?php foreach ($picklist->get_properties() as $property) { ?>
		<dt><?= $property->get_name() ?></dt>
		<dd><?= auto_link($property->get_value()) ?></dd>
	<?php } ?>
</dl>

<!-- Lot Size Selector -->
<form method="GET" action="/pick">
	<input type="hidden" name="archive" value="<?= $picklist->get_archive_name() ?>">
	Lot Size: <input type="number" id="lotsize" name="lotsize" min="1" <?= ($lot_size > 1) ? 'value="' . $lot_size . '"' : '' ?>>

	<button type="submit">Apply</button>
</form>
<br>

<hr>

<!-- Actual Pick List -->
<?php foreach ($picklist->get_categories() as $category) { ?>
	<div id="<?= $category->get_id() ?>">
		<h3><?= $category->get_name() ?></h3>

		<table border="1" cellpadding="6">
			<thead style="background-color: #E0E0E0;">
				<tr>
					<th>Picked</th>
					<th>Quantity</th>
					<th>Part #</th>
					<th>Value</th>
					<th>Reference Designators</th>
					<th>Description</th>
					<th>Package</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($category->get_components() as $component) { ?>
					<tr id="<?= $component->get_id() ?>">
						<td align="center"><input type="checkbox"></td>
						<td align="center"><?= $component->get_quantity() * $lot_size ?></td>
						<th><?= $component->get_name() ?></th>
						<td align="center"><?= $component->get_value() ?></td>
						<td>
							<?php foreach ($component->get_refdes() as $refdes) { ?>
								<?= $refdes ?>
							<?php } ?>
						</td>
						<td><?= $component->get_description() ?></td>
						<td><?= $component->get_package() ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<br>
<?php } ?>

<hr>

<!-- Source Code -->
<textarea rows="20" cols="100"><?= $picklist->get_source_code() ?></textarea>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>