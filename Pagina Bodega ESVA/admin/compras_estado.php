<?php
require_once '../db.php';

$id = $_GET['id'];
$accion = $_GET['accion'];

$estado = 'Registrada';
if ($accion === 'pagada') $estado = 'Pagada';
if ($accion === 'anulada') $estado = 'Anulada';

$conn->query("UPDATE compras SET estado='$estado' WHERE id_compra=$id");

header("Location: compras.php");
exit;
?>
