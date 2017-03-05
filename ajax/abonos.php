<?php
if (empty($_POST['tot'])) exit;

$tot = $_POST['tot'];

include_once '../db.php';
$db = new MyDB();

//$porcEnganche = $db->getConfig('porcEnganche');
$tasaFinanc = $db->getConfig('tasaFinanciamiento');
$plazoM = $db->getConfig('plazoM');
?>

<table class="tbl">
	<thead>
		<tr><th colspan="5">ABONOS MENSUALES</th></tr>
	</thead>
	<tbody>
		<?php
		
		$PrecioContado = $tot / (1 + (($tasaFinanc * $plazoM) / 100));

		for ($plazo=3; $plazo <= $plazoM; $plazo+=3) { 
			$TotalaPagar = $PrecioContado * (1 + ($tasaFinanc * $plazo) / 100);
			$ImporteAbono = $TotalaPagar / $plazo;
			$ImporteAhorra = $tot - $TotalaPagar;
			?>
			<tr>
				<td><?=$plazo?> ABONOS DE</td>
				<td>$ <?=number_format($ImporteAbono, 2, ".", ",");?></td>
				<td>TOTAL A PAGAR $ <?=number_format($TotalaPagar, 2, ".", ",");?></td>
				<td>SE AHORRA $ <?=number_format($ImporteAhorra, 2, ".", ",");?></td>
				<td><input type="radio" name="plazo" value="<?=$plazo?>" onclick="chk(this)"></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>