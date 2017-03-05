<?php
if ($_SERVER['SCRIPT_NAME'] == '/t.php') {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	include_once '404.php';
	die();
}

/**
* Clase contenedora de partes que se podrán incluir en varios archivos
*/
class Things
{
	// Enlaces a hojas de estilos
	var $css = '<link rel="stylesheet" type="text/css" href="css/g.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

	// Contenido del menú superior
	public function topContent()
	{
		?>
		<div id="top">
			<div class="left">
				<div class="op inicio">Inicio</div>
			</div>
			<div class="right">Fecha: <?=date('d/m/Y');?></div>
		</div>
		<ul class="mnu">
			<a href="/"><li style="border-bottom: 1px solid #e0e0e0;">Ventas</li></a>
			<a href="/clientes.php"><li>Clientes</li></a>
			<a href="/articulos.php"><li>Articulos</li></a>
			<a href="/configuracion.php"><li>Configuración</li></a>
		</ul>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
		<script type="text/javascript">
			$('.mnu a[href="'+document.location.pathname+'"] li').addClass('s');
			$('.op.inicio').click(function(event) {
				$('.mnu').toggle();
			});
		</script>
		<?php
	}
}
