<?php
include_once '../db.php';
$db = new MyDB();
?>
<form method="post">
	<div class="caja">
		<div class="cajatop">
			<?php
			if (empty($_GET['clv'])) {
				echo "Registro de Clientes";
				$clv = str_pad($db->getNextId('clientes'), 4, '0', STR_PAD_LEFT);
				$cliente = false;
			} else {
				echo "Editando Cliente";
				$clv = str_pad($_GET['clv'], 4, '0', STR_PAD_LEFT) .'<input type="hidden" name="clv" value="'. $_GET['clv'] .'">';
				$cliente = $db->getCliente($_GET['clv']);
			}
			?>
		</div>
		<div class="cajabody">
			<div class="label folio">Clave: <?=$clv?></div>
			<table>
				<tr>
					<td class="label">Nombre:</td>
					<td><input type="name" name="nom"<?=($cliente)?' value="'.$cliente['nombre'].'"':''?> placeholder="Nombre" required></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Apellido Paterno:</td>
					<td><input type="name" name="ap_p"<?=($cliente)?' value="'.$cliente['apellido_paterno'].'"':''?> placeholder="Apellido paterno" required></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Apellido Materno:</td>
					<td><input type="name" name="ap_m"<?=($cliente)?' value="'.$cliente['apellido_materno'].'"':''?> placeholder="Apellido materno" required></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="label">RFC:</td>
					<td><input type="text" onchange="this.value = this.value.toUpperCase()" name="rfc"<?=($cliente)?' value="'.$cliente['rfc'].'"':''?> minlength="13" maxlength="13" placeholder="RFC" required></td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="result"></div>
	<div class="btns">
		<input type="button" class="no" onclick="seguro()" value="Cancelar">
		<input type="submit" class="ok" value="Guardar">
	</div>
</form>

<script type="text/javascript">
	function seguro() {
		if (confirm('¿Seguro que desea salir de la pantalla actual?')) document.location = '/clientes.php';
	}
</script>