<?php
include_once '../db.php';
$mydb = new MyDB();
$n = 0;
if (!empty($_POST['tasa'])) {
	if ($mydb->setConfig('tasaFinanciamiento', $_POST['tasa'])) $n++;
}
if (!empty($_POST['enganche'])) {
	if ($mydb->setConfig('porcEnganche', $_POST['enganche'])) $n++;
}
if (!empty($_POST['plazo'])) {
	if ($mydb->setConfig('plazoM', $_POST['plazo'])) $n++;
}
if ($n > 0) echo "Bien Hecho. La configuración ha sido registrada";
else echo "Revisa los datos ingresados";

