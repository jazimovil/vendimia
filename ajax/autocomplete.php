<?php
if (empty($_POST['t']) || empty($_POST['txt'])) exit;

include_once '../db.php';
$db = new MyDB();
switch ($_POST['t']) {
	case 'c':
		$result = $db->searchClientes($_POST['txt']);
		foreach ($result as $v) {	
		    echo '<div class="elemento" rfc="'. $v['rfc']. '" clv="'. $v['clave'] .'">'. $v['nombre'] . ' ' . $v['apellido_paterno'] . ' ' . $v['apellido_materno'] .'</div>';
		}
		break;
	case 'a':
		$result = $db->searchArticulos($_POST['txt']);
		foreach ($result as $v) {	
		    echo '<div class="elemento" pre="'.$v['precio'].'" exi="'.$v['existencia'].'" clv="'. $v['clave'] .'">'. $v['descripcion'] .'</div>';
		}
		break;
}

