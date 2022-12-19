<?php define('PAGE_TITLE', 'Upload'); ?>
<?php define('PAGE_SUBTITLE', 'Try out on your own pick lists'); ?>
<?php require(__DIR__ . "/private/templates/head.php"); ?>

<!-- File Submission -->
<h2 class="section">Upload File</h2>
<form method="POST" action="<?= href('/pick') ?>" enctype="multipart/form-data">
	<input type="file" id="archive-file" name="archive-file" required>
	<button type="submit">Submit Archive</button>
</form>
<br>

<!-- Text Submission -->
<h2 class="section">Upload Text</h2>
<form method="POST" action="<?= href('/pick') ?>" enctype="multipart/form-data">
	<textarea id="archive-text" name="archive-text" rows="30" cols="80" required>Name: Example Archive
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
R10 R12</textarea>
	<br>
	<br>
	<button type="submit">Submit Source Code</button>
</form>

<?php require(__DIR__ . "/private/templates/footer.php"); ?>