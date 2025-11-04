<?php
require_once '../db.php';

if (isset($_POST['id_presentacion'], $_POST['campo'], $_POST['valor'])) {
    $id = intval($_POST['id_presentacion']);
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $permitidos = ['precio_venta', 'stock'];
    if (in_array($campo, $permitidos)) {
        $sql = "UPDATE presentaciones_producto SET $campo = ? WHERE id_presentacion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $valor, $id);
        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "Error DB";
        }
    } else {
        echo "Campo no permitido";
    }
} else {
    echo "Datos incompletos";
}
?>
