<?php
require_once '../db.php';

$id_proveedor = $_POST['id_proveedor'];
$id_empleado = $_POST['id_empleado'];
$productos = $_POST['productos'];
$cantidades = $_POST['cantidades'];
$precios = $_POST['precios'];

$total = 0;
foreach ($precios as $i => $precio) {
    $total += $precio * $cantidades[$i];
}

// Insertar compra principal
$stmt = $conn->prepare("INSERT INTO compras (id_proveedor, id_empleado, total) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $id_proveedor, $id_empleado, $total);
$stmt->execute();
$id_compra = $conn->insert_id;

// Insertar detalle
for ($i = 0; $i < count($productos); $i++) {
    $conn->query("INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio_unitario)
                  VALUES ($id_compra, {$productos[$i]}, {$cantidades[$i]}, {$precios[$i]})");
}

header("Location: compras.php");
exit;
?>
