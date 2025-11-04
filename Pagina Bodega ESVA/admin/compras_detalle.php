<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

if (!isset($_GET['id'])) {
    header("Location: compras.php");
    exit;
}

$id_compra = intval($_GET['id']);

// 🔹 Obtener información de la compra principal
$compra = $conn->query("
    SELECT c.*, 
           p.nombre_empresa, 
           e.nombre_empleado
    FROM compras c
    LEFT JOIN proveedores p ON c.id_proveedor = p.id_proveedor
    LEFT JOIN empleados e ON c.id_empleado = e.id_empleado
    WHERE c.id_compra = $id_compra
")->fetch_assoc();

if (!$compra) {
    echo "<p>❌ Compra no encontrada.</p>";
    exit;
}

// 🔹 Obtener detalle de productos
$detalles = $conn->query("
    SELECT d.*, pr.nombre_producto, pr.descripcion, pr.imagen
    FROM detalle_compra d
    LEFT JOIN productos pr ON d.id_producto = pr.id_producto
    WHERE d.id_compra = $id_compra
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Compra #<?= $id_compra ?> - Bodega ESVA</title>
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .container {
            margin-left: 260px;
            padding: 25px;
        }

        h1, h2 {
            color: #333;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 25px;
        }

        .info p {
            margin: 5px 0;
            font-size: 15px;
        }

        .estado {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .estado.Registrada { background: #cce5ff; color: #004085; }
        .estado.Pagada { background: #d4edda; color: #155724; }
        .estado.Anulada { background: #f8d7da; color: #721c24; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background: #4b0082;
            color: white;
        }

        img {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
        }

        .volver {
            display: inline-block;
            background: #4b0082;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .volver:hover {
            background: #6f42c1;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container">
    <a href="compras.php" class="volver">⬅ Volver a Compras</a>
    <h1>Detalle de la Compra #<?= $id_compra ?></h1>

    <div class="card">
        <h2>📋 Información General</h2>
        <div class="info">
            <p><strong>Proveedor:</strong> <?= htmlspecialchars($compra['nombre_empresa']) ?></p>
            <p><strong>Empleado Responsable:</strong> <?= htmlspecialchars($compra['nombre_empleado']) ?></p>
            <p><strong>Fecha de Compra:</strong> <?= htmlspecialchars($compra['fecha_compra']) ?></p>
            <p><strong>Total:</strong> S/ <?= number_format($compra['total'], 2) ?></p>
            <p><strong>Estado:</strong> <span class="estado <?= $compra['estado'] ?>"><?= $compra['estado'] ?></span></p>
        </div>
    </div>

    <div class="card">
        <h2>📦 Productos Comprados</h2>
        <table>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario (S/)</th>
                <th>Subtotal (S/)</th>
            </tr>

            <?php
            $total = 0;
            while ($d = $detalles->fetch_assoc()) {
                $subtotal = $d['cantidad'] * $d['precio_unitario'];
                $total += $subtotal;
                $img = !empty($d['imagen']) ? "../uploads/" . $d['imagen'] : "../assets/img/no-image.png";

                echo "
                <tr>
                    <td><img src='{$img}' alt='Imagen Producto'></td>
                    <td>{$d['nombre_producto']}</td>
                    <td>{$d['descripcion']}</td>
                    <td>{$d['cantidad']}</td>
                    <td>{$d['precio_unitario']}</td>
                    <td>" . number_format($subtotal, 2) . "</td>
                </tr>";
            }
            ?>
        </table>

        <p class="total">💰 Total General: <strong>S/ <?= number_format($total, 2) ?></strong></p>
    </div>
</div>

</body>
</html>
