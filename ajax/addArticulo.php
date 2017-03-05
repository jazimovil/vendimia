<?php
if (!empty($_POST['clv'])) {
	include_once '../db.php';
	$db = new MyDB();
	$articulo = $db->getArticulo($_POST['clv'], true);
	$tasaFin = $db->getConfig('tasaFinanciamiento');
	$plazoM = $db->getConfig('plazoM');
	if ($articulo) {
		$precioFinal = number_format($articulo['precio'] * (1 + ($tasaFin * $plazoM) / 100), 2, ".", "");
		?>
		<tr clv="<?=$articulo['clave']?>">
			<td><?=$articulo['descripcion']?><input type="hidden" name="articulos[clave][]" value="<?=$articulo['clave']?>"></td>
			<td><?=$articulo['modelo']?></td>
			<td><input type="number" onchange="setImporte(this)" autofocus name="articulos[cantidad][]" min="1" max="<?=$articulo['existencia']?>" value="1"></td>
			<td class="p">$ <?=$precioFinal?></td>
			<td class="i">$ <?=$precioFinal?></td>
			<td><i class="fa fa-times" onclick="delArticulo(this)"></i></td>
		</tr>
		<?php
	} else echo "0";
}