<?php
if ($_SERVER['SCRIPT_NAME'] == '/db.php') {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	include_once '404.php';
	die();
}

/**
* MyDB
*/
class MyDB
{
	var $enlace;

	function __construct()
	{
		$this->enlace = mysqli_connect("localhost", "vendimia_user", "HY6ty2e6g1PKmrp2", "vendimia");
	}

	public function setConfig($id, $valor)
	{
		try {
			$id = $this->escape($id);
			$valor = $this->escape($valor);
			$sql = "INSERT INTO config (id, valor) VALUES ('$id', '$valor');";
			$r = $this->enlace->query($sql);
			if ($r) return true;
			else {
				$sql = "UPDATE config SET valor = '$valor' WHERE id = '$id';";
				$r = $this->enlace->query($sql);
				if ($r) return true;
			}
		} catch (Exception $e) {
			//fwrite($this->f, $this->now . ".\n" . $e . "\n\n");
		}
		return false;
	}

	public function getVentas()
	{
		$tasaFinanc = $this->getConfig('tasaFinanciamiento');  // 2.8
		$porcEnganche = $this->getConfig('porcEnganche');  // 20 %
		$plazoM = $this->getConfig('plazoM');  // 12 meses

		$n = 1 + ($tasaFinanc * $plazoM) / 100;  // 1.336

		$sql = "SELECT v.clave folio, c.clave clave, 
			concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) nombre, 
			sum(precio * $n * cantidad) total,
			fecha
			FROM ventas v
			INNER JOIN clientes c ON clave_cliente = c.clave
			INNER JOIN ventas_articulos ON clave_venta = v.clave
			INNER JOIN articulos a ON clave_articulo = a.clave
			GROUP BY v.clave";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return false;
		return $r->fetch_all(MYSQLI_ASSOC);
	}

	public function setVenta($folio, $cliente, $plazo, $fecha)
	{
		$folio = $this->escape($folio);
		$cliente = $this->escape($cliente);
		$plazo = $this->escape($plazo);
		$sql = "INSERT INTO ventas (clave, clave_cliente, plazo, fecha) VALUES ('$folio', '$cliente', '$plazo', '$fecha');";
		$r = $this->enlace->query($sql);
		if ($r == 1) return true;
		return false;
	}
	public function setArticuloVenta($folio, $articulo, $cantidad)
	{
		$folio = $this->escape($folio);
		$articulo = $this->escape($articulo);
		$cantidad = $this->escape($cantidad);
		$sql = "UPDATE articulos SET existencia = existencia - $cantidad WHERE clave = '$articulo';";
		$r = $this->enlace->query($sql);
		$sql = "INSERT INTO ventas_articulos (clave_venta, clave_articulo, cantidad) VALUES ('$folio', '$articulo', '$cantidad');";
		$r = $this->enlace->query($sql);
		if ($r == 1) return true;
		return false;
	}

	public function getConfig($id)
	{
		$id = $this->escape($id);
		$sql = "SELECT valor FROM config WHERE id = '$id';";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return false;
		return $r->fetch_array(MYSQLI_ASSOC)['valor'];
	}

	public function getArticulos()
	{
		$sql = "SELECT * FROM articulos;";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return array();
		return $r->fetch_all(MYSQLI_ASSOC);
	}

	public function getArticulo($clave, $solo_disp = false)
	{
		$clave = $this->escape($clave);
		if ($solo_disp) $sql = "SELECT * FROM articulos WHERE clave = '$clave' and existencia > 0;";
		else $sql = "SELECT * FROM articulos WHERE clave = '$clave';";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return false;
		return $r->fetch_array(MYSQLI_ASSOC);
	}

	public function getClientes()
	{
		$sql = "SELECT * FROM clientes;";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return array();
		return $r->fetch_all(MYSQLI_ASSOC);
	}

	public function searchClientes($txt)
	{
		$txt = $this->escape($txt);
		$sql = "SELECT * FROM clientes WHERE concat(clave, nombre, apellido_paterno, apellido_materno, rfc) LIKE '%$txt%';";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return false;
		return $r->fetch_all(MYSQLI_ASSOC);
	}

	public function searchArticulos($txt)
	{
		$txt = $this->escape($txt);
		$sql = "SELECT * FROM articulos WHERE concat(clave, descripcion, modelo) LIKE '%$txt%';";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return array();
		return $r->fetch_all(MYSQLI_ASSOC);
	}

	public function getCliente($clave)
	{
		$clave = $this->escape($clave);
		$sql = "SELECT * FROM clientes WHERE clave = '$clave';";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return false;
		return $r->fetch_array(MYSQLI_ASSOC);
	}

	// Para obtener el proximo ID que corresponde a cierta tabla
	public function getNextId($tbl)
	{
		$sql = "SELECT clave + 1 n FROM $tbl ORDER BY clave DESC LIMIT 1;";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return 1;
		return $r->fetch_array(MYSQLI_ASSOC)['n'];
	}

	/*public function getNextClientId()
	{
		$id = $this->escape($id);
		$sql = "SELECT clave + 1 n FROM clientes ORDER BY clave DESC LIMIT 1;";
		$r = $this->enlace->query($sql);
		if (!$r || !$r->num_rows) return 1;
		return $r->fetch_array(MYSQLI_ASSOC)['n'];
	}*/

	public function setCliente($clv, $nom, $ap_p, $ap_m, $rfc)
	{
		try {
			$nom = $this->escape($nom);
			$ap_p = $this->escape($ap_p);
			$ap_m = $this->escape($ap_m);
			$rfc = $this->escape($rfc);
			if (!$clv) {
				$sql = "INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, rfc) VALUES ('$nom', '$ap_p', '$ap_m', '$rfc');";
			} else {
				$sql = "UPDATE clientes SET nombre = '$nom', apellido_paterno = '$ap_p', apellido_materno = '$ap_m', rfc = '$rfc' WHERE clave = '$clv';";
			}
			$r = $this->enlace->query($sql);
			if ($r == 1) return true;
		} catch (Exception $e) {
			//fwrite($this->f, $this->now . ".\n" . $e . "\n\n");
		}
		return false;
	}

	public function setArticulo($clv, $des, $mod, $pre, $exi)
	{
		try {
			$des = $this->escape($des);
			$mod = $this->escape($mod);
			$pre = $this->escape($pre);
			$exi = $this->escape($exi);
			if (!$clv) {
				$sql = "INSERT INTO articulos (descripcion, modelo, precio, existencia) VALUES ('$des', '$mod', '$pre', '$exi');";
			} else {
				$sql = "UPDATE articulos SET descripcion = '$des', modelo = '$mod', precio = '$pre', existencia = '$exi' WHERE clave = '$clv';";
			}
			$r = $this->enlace->query($sql);
			if ($r == 1) return true;
		} catch (Exception $e) {
			//fwrite($this->f, $this->now . ".\n" . $e . "\n\n");
		}
		return false;
	}

	public function escape($value) {
		return $this->enlace->escape_string($value);
	}
}





/*
// sum(precio * $n * cantidad) = importe,
// (($porcEnganche / 100) * importe) = eng,
// (eng * ($n - 1) = bonEng,
// (importe - (($porcEnganche / 100) * importe) - (eng * ($n - 1)) = tot,
// tot / $n = PrecioContado,
// PrecioContado * (1 + ($tasaFinanc * plazo) / 100) = total,
*/