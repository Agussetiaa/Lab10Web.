<?php
include "form.php";
include "Database.php";

echo "<html><head><title>Mahasiswa</title></head><body>";

$form = new Form("process.php", "Submit");

$form->addField("nim", "Nim");
$form->addField("nama", "Nama");
$form->addField("alamat", "Alamat");

echo "<h3>Silakan isi formulir berikut ini:</h3>";
$form->displayForm();

echo "</body></html>";
?>
