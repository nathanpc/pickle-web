	</main>

	<!-- Footer -->
	<footer class="container">
		<hr>
		<div class="container">
			<div class="row">
				<div class="col">
					<a href="https://innoveworkshop.com/">Innove Workshop</a>
				</div>

				<div class="col">
					<form METHOD="POST">
						<div class="input-group input-group-sm">
							<select class="form-control" name="theme">
								<?php foreach (get_theme_list() as $theme) { ?>
									<option <?php if ($router->get_theme() == $theme) { echo "selected"; } ?>>
										<?= $theme ?>
									</option>
								<?php } ?>
							</select>

							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="submit">Apply</button>
							</div>
						</div>
					</form>
				</div>

				<div class="col" style="text-align: right;">
					Designed and built by <a href="https://nathancampos.me/">@nathanpc</a>
				</div>
			</div>

			<br>
		</div>
	</footer>
	</body>

	</html>