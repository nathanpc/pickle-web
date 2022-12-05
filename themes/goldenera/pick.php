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

<?php if (false) { ?>

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

<?php } ?>

<!-- Actual Pick List -->
<?php foreach ($picklist->get_categories() as $category) { ?>
	<!-- Category -->
	<div id="<?= $category->get_id() ?>" class="picklist-category">
		<h3><?= $category->get_name() ?></h3>

		<!-- Components -->
		<table>
			<tbody>
				<?php foreach ($category->get_components() as $component) { ?>
					<tr id="<?= $component->get_id() ?>" class="pick-item">
						<!-- Pick State -->
						<td class="picked"><input type="checkbox"></td>

						<!-- Quantity -->
						<td class="quantity">
							<?= $component->get_quantity() * $lot_size ?>
						</td>

						<td class="desc-col">
							<div class="title">
								<!-- Title -->
								<?= $component->get_name() ?>

								<?php if ($component->has_value()) { ?>
									<!-- Value -->
									<span class="value"><?= $component->get_value() ?></span>
								<?php } ?>
								<?php if ($component->has_package()) { ?>
									<!-- Package -->
									<span class="package"><?= $component->get_package() ?></span>
								<?php } ?>
							</div>

							<?php if ($component->has_description()) { ?>
								<!-- Description -->
								<div class="desc"><?= $component->get_description() ?></div>
							<?php } ?>

							<!-- Reference Designators -->
							<div class="refdes-list">
								<?php foreach ($component->get_refdes() as $refdes) { ?>
									<span class="refdes"><?= $refdes ?></span>
								<?php } ?>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<br>
<?php } ?>

<!-- Source Code -->
<div id="source-code" class="hidden">
	<hr>
	<textarea rows="20" cols="100"><?= $picklist->get_source_code() ?></textarea>
	<br>
</div>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>