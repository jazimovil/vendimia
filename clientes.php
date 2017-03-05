<?php
include_once 'db.php';
$db = new MyDB();

if (!empty($_POST['rfc'])) {
	$db->setCliente((empty($_POST['clv']))?0:$_POST['clv'], $_POST['nom'], $_POST['ap_p'], $_POST['ap_m'], $_POST['rfc']);
	header('location:/clientes.php');
	exit;
}

include_once 't.php';
$t = new Things();

$clientes = $db->getClientes();
?>
<!DOCTYPE html>
<html>
<head>
	<title>La Vendimia - Clientes</title>
	<?=$t->css?>
</head>
<body>
	<?=$t->topContent()?>

	<div id="content">
	<button id="btn-new" onclick="newClient()"><i class="fa fa-plus-circle"></i> Nuevo Cliente</button>
		<h3>Clientes registrados</h3>
		<table class="tbl" cellspacing="0">
			<thead>
				<tr>
					<th>Clave cliente</th>
					<th>Nombre</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($clientes as $cliente) {
					?>
					<tr>
						<td><?=$cliente['clave']?></td>
						<td><?=$cliente['nombre']?> <?=$cliente['apellido_paterno']?> <?=$cliente['apellido_materno']?></td>
						<td><i class="fa fa-edit op" onclick="edit_client(<?=$cliente['clave']?>)"></i></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>

	</div>
	<script type="text/javascript">
		function edit_client(clv) {
			$.get('/ajax/newClientForm.php?clv='+clv, function(data) {
				$('#content').html(data);
			});
		}
		function newClient() {
			$('#content').load('/ajax/newClientForm.php');
		}
	</script>

</body>
</html>