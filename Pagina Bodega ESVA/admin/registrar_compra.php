<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_proveedor = $_POST['id_proveedor'];
    $id_empleado = $_POST['id_empleado'];
    $estado = $_POST['estado'];
    $total = 0;

    // Crear carpeta de imágenes si no existe
    $uploadDir = '../uploads/productos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // 1️⃣ Crear compra
    $stmt = $conn->prepare("INSERT INTO compras (id_proveedor, id_empleado, total, estado) VALUES (?, ?, 0, ?)");
    $stmt->bind_param("iis", $id_proveedor, $id_empleado, $estado);
    $stmt->execute();
    $id_compra = $conn->insert_id;

    // 2️⃣ Iterar productos
    for ($i = 0; $i < count($_POST['nombre_producto']); $i++) {

        $nombre_producto = trim($_POST['nombre_producto'][$i]);
        $descripcion = trim($_POST['descripcion'][$i]);
        $id_categoria = $_POST['id_categoria'][$i];
        $nombre_presentacion = trim($_POST['nombre_presentacion'][$i]);
        $cantidad_contenida = intval($_POST['cantidad_contenida'][$i]);
        $unidad_medida = trim($_POST['unidad_medida'][$i]);
        $precio_compra = floatval($_POST['precio_compra'][$i]);
        $cantidad = intval($_POST['cantidad'][$i]);
        $fecha_vencimiento = $_POST['fecha_vencimiento'][$i];
        $subtotal = $precio_compra * $cantidad;
        $total += $subtotal;

        // Imagen del producto
        $imagenRuta = null;
        if (isset($_FILES['imagen']['name'][$i]) && $_FILES['imagen']['name'][$i] != '') {
            $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name'][$i]);
            $rutaDestino = $uploadDir . $nombreArchivo;
            move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $rutaDestino);
            $imagenRuta = 'uploads/productos/' . $nombreArchivo;
        }

        // 3️⃣ Verificar si el producto ya existe
        $check = $conn->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
        $check->bind_param("s", $nombre_producto);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->bind_result($id_producto);
            $check->fetch();
        } else {
            $insertProd = $conn->prepare("
                INSERT INTO productos (id_categoria, id_proveedor, nombre_producto, descripcion, imagen, unidad_base)
                VALUES (?, ?, ?, ?, ?, 'unidad')
            ");
            $insertProd->bind_param("iisss", $id_categoria, $id_proveedor, $nombre_producto, $descripcion, $imagenRuta);
            $insertProd->execute();
            $id_producto = $conn->insert_id;
        }

        // 4️⃣ Crear o actualizar presentación
        $checkPres = $conn->prepare("
            SELECT id_presentacion FROM presentaciones_producto 
            WHERE id_producto = ? AND nombre_presentacion = ?
        ");
        $checkPres->bind_param("is", $id_producto, $nombre_presentacion);
        $checkPres->execute();
        $checkPres->store_result();

        if ($checkPres->num_rows > 0) {
            $checkPres->bind_result($id_presentacion);
            $checkPres->fetch();
            $conn->query("UPDATE presentaciones_producto SET stock = stock + $cantidad WHERE id_presentacion = $id_presentacion");
        } else {
            $precio_venta = $precio_compra * 1.3; // margen 30%
            $insertPres = $conn->prepare("
                INSERT INTO presentaciones_producto (id_producto, nombre_presentacion, cantidad_contenida, unidad_medida, precio_venta, stock)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $insertPres->bind_param("isdsdi", $id_producto, $nombre_presentacion, $cantidad_contenida, $unidad_medida, $precio_venta, $cantidad);
            $insertPres->execute();
            $id_presentacion = $conn->insert_id;
        }

        // 5️⃣ Insertar detalle de compra
        $stmtDet = $conn->prepare("
            INSERT INTO detalle_compra (id_compra, id_presentacion, cantidad, precio_compra, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtDet->bind_param("iiidd", $id_compra, $id_presentacion, $cantidad, $precio_compra, $subtotal);
        $stmtDet->execute();

        // 6️⃣ Crear lote
        $codigo_lote = "L" . time() . rand(100, 999);
        $fecha_ingreso = date('Y-m-d');
        $stmtLote = $conn->prepare("
            INSERT INTO lotes (id_presentacion, codigo_lote, fecha_ingreso, fecha_vencimiento, cantidad_inicial, cantidad_actual)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtLote->bind_param("isssii", $id_presentacion, $codigo_lote, $fecha_ingreso, $fecha_vencimiento, $cantidad, $cantidad);
        $stmtLote->execute();
    }

    // 7️⃣ Actualizar total de la compra
    $conn->query("UPDATE compras SET total = $total WHERE id_compra = $id_compra");

    echo "<script>alert('✅ Compra registrada correctamente con estado $estado');window.location='compras.php';</script>";
    exit;
}
?>
