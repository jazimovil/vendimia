<?php
include_once '../db.php';
$db = new MyDB();
?>
<form method="post">
	<div class="caja">
		<div class="cajatop">
			<?php
			$articulo = false;
			if (empty($_GET['clv'])) {
				echo "Registro de Articulos";
				$clv = str_pad($db->getNextId('articulos'), 4, '0', STR_PAD_LEFT);
			} else {
				echo "Editando Articulo";
				$clv = str_pad($_GET['clv'], 4, '0', STR_PAD_LEFT) .'<input type="hidden" name="clv" value="'. $_GET['clv'] .'">';
				$articulo = $db->getArticulo($_GET['clv'], false);
			}
			?>
		</div>
		<div class="cajabody">
			<div class="label folio">Clave: <?=$clv?></div>
			<table>
				<tr>
					<td class="label">Descripción:</td>
					<td><input type="text" name="des"<?=($articulo)?' value="'.$articulo['descripcion'].'"':''?> placeholder="Descripción" required></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Modelo:</td>
					<td><input type="text" name="mod"<?=($articulo)?' value="'.$articulo['modelo'].'"':''?> placeholder="Modelo"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Precio:</td>
					<td><input type="number" step="0.01" min="0" name="pre"<?=($articulo)?' value="'.$articulo['precio'].'"':''?> placeholder="Precio" required></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Existencia:</td>
					<td>
						<input type="text" name="exi"<?=($articulo)?' value="'.$articulo['existencia'].'"':''?> placeholder="Existencia" required>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="result"></div>
	<div class="btns">
		<input type="button" class="no" onclick="if (confirm('¿Seguro que desea salir de la pantalla actual?')) document.location = '/articulos.php';" value="Cancelar">
		<input type="submit" class="ok" value="Guardar">
	</div>
</form>
