<?php define('PAGE_TITLE', 'Upload'); ?>
<?php require(__DIR__ . "/../templates/head.php"); ?>

<h3>
	<?= PAGE_TITLE ?>
	<small class="text-muted">Try out on your own pick lists</small>
</h3>

<br>

<!-- Upload or Submit Text Form -->
<div class="card text-center">
	<!-- Card Tab Bar -->
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="file-tab" data-toggle="tab" data-target="#file" type="button" role="tab" aria-controls="file" aria-selected="true">
					File
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="text-tab" data-toggle="tab" data-target="#text" type="button" role="tab" aria-controls="text" aria-selected="true">
					Text
				</button>
			</li>
		</ul>
	</div>

	<!-- Tabbed Contents -->
	<div class="card-body tab-content">
		<!-- File Tab -->
		<div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="file-tab">
			<form method="POST" action="<?= href('/pick') ?>" enctype="multipart/form-data">
				<!-- Archive File Picker -->
				<div class="custom-file mb-3">
					<input type="file" class="custom-file-input" id="archive-file" name="archive-file" required>
					<label class="custom-file-label" for="archive-file">
						Choose a PickLE archive...
					</label>
				</div>

				<button class="btn btn-primary" type="submit">
					Submit Archive
				</button>
			</form>
		</div>

		<!-- Text Tab -->
		<div class="tab-pane fade" id="text" role="tabpanel" aria-labelledby="text-tab">
			<form method="POST" action="<?= href('/pick') ?>" enctype="multipart/form-data">
				<!-- Archive Text Area -->
				<div class="mb-3">
					<textarea class="form-control text-monospace" id="archive-text" name="archive-text" rows="20" placeholder="Paste your PickLE archive contents here..." required>Name: Example Archive
Revision: A
Description: A very detailed description.
Website: https://innoveworkshop.com/
Source-Control: https://github.com/nathanpc/pickle-web
License: MIT

---

Connectors:
[ ]     2       B6B-XH-A        "Wire-to-Board 2.5mm pitch top mount connector" [B6B-XH-A]
CONN0 CONN1


Ceramic Capacitor:
[ ]     7       C0805   (100n)  "Ceramic Capacitor"     [C0805]
C2 C3 C6 C7 C8 C9 C10

[ ]     2       C0805   (1u)    "Ceramic Capacitor"     [C0805]
C1 C5


Resistor:
[ ]     9       R0805   (10k)   "Resistor"      [R0805]
R1 R3 R7 R13 R14 R15 R16 R18 R20

[ ]     1       R0805   (130)   "Resistor"      [R0805]
R11

[ ]     6       R0805   (1k)    "Resistor"      [R0805]
R2 R4 R5 R6 R17 R19

[ ]     2       R0805   (720)   "Resistor"      [R0805]
R10 R12
					</textarea>
				</div>

				<button class="btn btn-primary" type="submit">
					Parse Text
				</button>
			</form>
		</div>
	</div>
</div>

<br>

<?php require(__DIR__ . "/../templates/footer.php"); ?>