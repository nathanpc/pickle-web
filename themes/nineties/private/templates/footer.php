	</div>

	<!-- Footer -->
	<div id="footer">
		<!-- Navigation -->
		<font size="3">
			<hr>
			<?= nav_item('Home', '/', 'index') ?> | <?= nav_item('Submit', '/upload', 'upload') ?> | <?= nav_item('Archive', '/archive', 'archive') ?> | <?= nav_item('About', '/about', 'about') ?>
			<br>
			Designed and built by <a href="https://nathancampos.me/">@nathanpc</a> for <a href="https://innoveworkshop.com/">Innove Workshop</a>.
			<form method="POST">
				<select name="theme">
					<?php foreach (get_theme_list() as $theme) { ?>
						<option <?php if ($router->get_theme() == $theme) {
									echo "selected";
								} ?>>
							<?= $theme ?>
						</option>
					<?php } ?>
				</select>

				<button type="submit">Apply</button>
			</form>
		</font>
	</div>
	</body>

	</html>