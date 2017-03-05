<?php
//if (empty($_POST[''])) exit;
#print_r($_POST);

include_once '../db.php';
$db = new MyDB();

$folio = $_POST['folio'];
$claveCliente = $_POST['clave'];
$articulos = $_POST['articulos'];
$plazo = $_POST['plazo'];

$r = $db->setVenta($folio, $claveCliente, $plazo, date('Y-m-d'));

if ($r) foreach ($articulos['clave'] as $k => $articulo) {
	$cantidad = $articulos['cantidad'][$k];
	$db->setArticuloVenta($folio, $articulo, $cantidad);
}