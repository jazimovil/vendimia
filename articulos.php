<?php
include_once 'db.php';
$db = new MyDB();

if (!empty($_POST['des'])) {
	$db->setArticulo((empty($_POST['clv']))?0:$_POST['clv'], $_POST['des'], $_POST['mod'], $_POST['pre'], $_POST['exi']);
	header('location:/articulos.php');
	exit;
}

include_once 't.php';
$t = new Things();

$articulos = $db->getArticulos();
?>
<!DOCTYPE html>
<html>
<head>
	<title>La Vendimia - Artículos</title>
	<?=$t->css?>
</head>
<body>
	<?=$t->topContent()?>

	<div id="content">
	<button id="btn-new" onclick="$('#content').load('/ajax/newArticleForm.php');"><i class="fa fa-plus-circle"></i> Nuevo Articulo</button>
		<h3>Articulos registrados</h3>
		<table class="tbl" cellspacing="0">
			<thead>
				<tr>
					<th>Clave Articulo</th>
					<th>Descripción</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($articulos as $articulo) {
					?>
					<tr>
						<td><?=$articulo['clave']?></td>
						<td><?=$articulo['descripcion']?></td>
						<td><i class="fa fa-edit op" onclick="edit_article(<?=$articulo['clave']?>)"></i></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>

	</div>
	<script type="text/javascript">
		function edit_article(clv) {
			$.get('/ajax/newArticleForm.php?clv='+clv, function(data) {
				$('#content').html(data);
			});
		}
	</script>

</body>
</html>