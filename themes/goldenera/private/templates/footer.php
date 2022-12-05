	</div>

	<!-- Footer -->
	<div id="footer">
		<!-- Theme Selector -->
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

		<!-- Copyright -->
		<p class="copyright">
			Designed and built by <a href="https://nathancampos.me/">@nathanpc</a>
			for <a href="https://innoveworkshop.com/">Innove Workshop</a>.
		</p>
	</div>
</body>
</html>