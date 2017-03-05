<?php

// Agregar clase contenedora de fragmentos
include_once 't.php';
$t = new Things();

// Agregar el conector a Base de Datos
include_once 'db.php';
$db = new MyDB();

// Obtener lista de Ventas realizadas
$ventas = $db->getVentas();

$porcEnganche = $db->getConfig('porcEnganche');
$tasaFinanciamiento = $db->getConfig('tasaFinanciamiento');
$plazoM = $db->getConfig('plazoM');

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>La Vendimia - Ventas</title>
	<?=$t->css?>
</head>
<body>
	<?=$t->topContent()?>
	<div id="content">

		<div id="uno">
			<button id="btn-new"><i class="fa fa-plus-circle"></i> Nueva Venta</button>
			<h3>Ventas activas</h3>
			<table class="tbl" cellspacing="0">
				<thead>
					<tr>
						<th>Folio Venta</th> 
						<th>Clave cliente</th>
						<th>Nombre</th>
						<th>Total</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($ventas) foreach ($ventas as $v) {
						$fecha = date("d/m/Y", strtotime($v['fecha']));
						$precio = number_format($v['total'], 2, ".", ",");
						echo "<tr><td>{$v['folio']}</td><td>{$v['clave']}</td><td>{$v['nombre']}</td><td>$ {$precio}</td><td>$fecha</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>

		<div id="dos" style="margin-top: 50px; display: none;">
			<div class="caja">
				<div class="cajatop">Registro de Ventas</div>
				<div class="cajabody">
					<form id="ventaTotal">
						<div class="label folio">Folio Venta: <?=str_pad($db->getNextId('ventas'), 4, '0', STR_PAD_LEFT)?><input type="hidden" name="folio" value="<?=$db->getNextId('ventas')?>"></div>

						<table>
							<tr>
								<td>Cliente:</td>
								<td>
									<input type="text" id="cliente" onkeyup="if (this.value.length > 2) searchC(this.value); else $('.sugerencias.c').hide();" placeholder="Buscar cliente..." autocomplete="off">
									<div class="sugerencias c"></div>
									<input type="hidden" id="clvC" name="clave">
								</td>
								<td>RFC: <span id="rfc"></span></td>
							</tr>
							<tr>
								<td>Articulo:</td>
								<td>
									<input type="text" id="articulo" onkeyup="if (this.value.length > 2) searchA(this.value)" placeholder="Buscar articulo..." autocomplete="off">
									<div class="sugerencias a"></div>
									<input type="hidden" id="clvA">
								</td>
								<td><button type="button" class="ok" onclick="if ($('#clvA').val() != '') addArticulo()"><i class="fa fa-plus"></i></button></td>
							</tr>
						</table>

						<table id="lista" style="width: 100%; border-top: 2px solid black; border-bottom: 2px solid black; padding-top: 8px; margin-top: 8px;" cellspacing="0">
							<thead>
								<tr>
									<th>Descripción Articulo</th>
									<th>Modelo</th>
									<th>Cantidad</th>
									<th>Precio</th>
									<th>Importe</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>

						<table class="tbl" style="text-align: right;">
							<tr><td>Enganche:</td><td id="eng">$ 0</td></tr>
							<tr><td>Bonificación Enganche:</td><td id="bonEng">$ 0</td></tr>
							<tr><td>Total:</td><td id="tot">$ 0</td></tr>
						</table>

						<div id="abonos" style="margin-top: 50px;"></div>
					</form>
				</div>
			</div>
			<div class="btns">
				<input type="button" class="no" onclick="seguro()" value="Cancelar">
				<input type="button" class="ok" onclick="ventas3(this)" value="Siguiente">
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/f.js"></script>
	<script type="text/javascript">
		function calcularDatos() {
			var imp, eng, bonEng, tot; // datos del articulo
			var lista = $('#lista tbody tr .i');  // lista de importes de articulos
			var enganche = 0, bonificacionEnganche = 0, total = 0;  // datos de la lista completa
			for (var i = 0; i < lista.length; i++) {
				imp = parseFloat($(lista[i]).text().replace('$ ', ''));
				
				eng = parseFloat(((<?=$porcEnganche?>/100)*imp).toFixed(2));
				bonEng = parseFloat((eng*((<?=$tasaFinanciamiento?> * <?=$plazoM?>)/100)).toFixed(2));
				tot = parseFloat((imp - eng - bonEng).toFixed(2));

				enganche += eng;
				bonificacionEnganche += bonEng;
				total += tot;
			}
			$('#eng').text('$ ' + parseFloat(enganche).toFixed(2));
			$('#bonEng').text('$ ' + parseFloat(bonificacionEnganche).toFixed(2));
			$('#tot').text('$ ' + parseFloat(total).toFixed(2));
		}

		function confirmExit() {
			return "Ha intentado salir de esta pagina. Si ha realizado algun cambio en los campos sin hacer clic en el boton Guardar, los cambios se perderan. Seguro que desea salir de esta pagina? ";
		}
	</script>
</body>
</html>