<?php
include_once 't.php';
$t = new Things();

include_once 'db.php';
$db = new MyDB();
?>
<!DOCTYPE html>
<html>
<head>
	<title>La Vendimia - Configuración</title>
	<?=$t->css?>
</head>
<body>
	<?=$t->topContent()?>

	<div id="content">
		<form style="text-align: right;" id="config" method="post">
			<div class="caja">
				<div class="cajatop">
					Configuración general
				</div>
				<div class="cajabody">
					<table>
						<tr>
							<td class="label">Tasa Financiamiento:</td>
							<td><input type="number" name="tasa" min="0" step="0.01" value="<?=$db->getConfig('tasaFinanciamiento')?>"></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="label">% Enganche:</td>
							<td><input type="number" name="enganche" value="<?=$db->getConfig('porcEnganche')?>"></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="label">Plazo Maximo:</td>
							<td>
							<select name="plazo">
								<option>12</option>
								<option>9</option>
								<option>6</option>
								<option>3</option>
							</select>
							<script>$('select[name="plazo"]').val('<?=$db->getConfig('plazoM')?>')</script>
							</td>
							<td>meses</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="result"></div>
			<input type="reset" class="no" value="Cancelar">
			<input type="submit" class="ok" value="Guardar">
		</form>
	</div>
	<script type="text/javascript">
		$('#config').submit(function(event) {
			$.post('/ajax/config.php', $(this).serialize(), function(data, textStatus, xhr) {
				$('#result').html(data);
			});
			return false;
		});
	</script>
</body>
</html>