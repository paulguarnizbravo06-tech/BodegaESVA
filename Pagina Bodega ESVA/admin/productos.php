<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

// Obtener todos los productos
$sql = "
SELECT p.id_producto, p.nombre_producto, p.descripcion, p.imagen, 
       c.nombre_categoria, pr.razon_social AS proveedor
FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
ORDER BY p.id_producto DESC;
";
$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main>
    <h2>📦 Productos Existentes</h2>
    <p style="font-size:1.1em; background:#f8f9fa; padding:10px; border-radius:8px; display:inline-block;">
        <strong>Total de productos:</strong> <?= $result->num_rows ?>
    </p>

    <table border="0" cellpadding="10" style="border-collapse: collapse; width:100%; background:#fff; box-shadow:0 0 5px rgba(0,0,0,0.1);">
        <tr style="background:#1e1e2f;color:white;">
            <th>ID</th>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Presentaciones</th>
            <th>Lotes</th>
            <th>Total Existencias</th>
        </tr>

        <?php
        $total_general_existencias = 0; // total general acumulado

        while($row = $result->fetch_assoc()):
            // Calcular total de existencias por producto (sumando todos los lotes asociados a sus presentaciones)
            $sql_stock = "
                SELECT SUM(l.cantidad_actual) AS total_existencias
                FROM lotes l
                INNER JOIN presentaciones_producto pp ON l.id_presentacion = pp.id_presentacion
                WHERE pp.id_producto = {$row['id_producto']}
            ";
            $res_stock = $conn->query($sql_stock);
            $stock_data = $res_stock->fetch_assoc();
            $total_existencias = $stock_data['total_existencias'] ?? 0;
            $total_general_existencias += $total_existencias;
        ?>
        <tr style="text-align:center;border-bottom:1px solid #ddd;">
            <td><?= $row['id_producto'] ?></td>
            <td>
                <?php if ($row['imagen']): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" width="60">
                <?php else: ?>
                    ❌ Sin imagen
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
            <td><?= $row['nombre_categoria'] ?? '—' ?></td>
            <td><?= $row['proveedor'] ?? '—' ?></td>
            <td>
                <?php
                $presentaciones = $conn->query("SELECT * FROM presentaciones_producto WHERE id_producto = {$row['id_producto']}");
                if ($presentaciones->num_rows > 0):
                ?>
                    <table style="width:100%;border-collapse:collapse;background:#f8f9fa;font-size:0.9em;">
                        <tr style="background:#e9ecef;">
                            <th>Presentación</th>
                            <th>Unidad</th>
                            <th>Cant.</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                        </tr>
                        <?php while($p = $presentaciones->fetch_assoc()): ?>
                        <?php
                            // stock de cada presentación desde lotes
                            $s = $conn->query("SELECT SUM(cantidad_actual) AS stock FROM lotes WHERE id_presentacion = {$p['id_presentacion']}");
                            $stock_pres = $s->fetch_assoc()['stock'] ?? 0;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nombre_presentacion']) ?></td>
                            <td><?= htmlspecialchars($p['unidad_medida']) ?></td>
                            <td><?= $p['cantidad_contenida'] ?></td>
                            <td>S/. <?= number_format($p['precio_venta'],2) ?></td>
                            <td><?= $stock_pres ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    — Sin presentaciones —
                <?php endif; ?>
            </td>
            <td>
                <?php
                $lotes = $conn->query("
                    SELECT codigo_lote, fecha_ingreso, fecha_vencimiento, cantidad_actual 
                    FROM lotes 
                    WHERE id_presentacion IN (
                        SELECT id_presentacion FROM presentaciones_producto WHERE id_producto = {$row['id_producto']}
                    )
                ");
                if ($lotes->num_rows > 0):
                ?>
                    <table style="width:100%;border-collapse:collapse;background:#f8f9fa;font-size:0.9em;">
                        <tr style="background:#e9ecef;">
                            <th>Lote</th>
                            <th>Ingreso</th>
                            <th>Vencimiento</th>
                            <th>Stock</th>
                        </tr>
                        <?php while($l = $lotes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($l['codigo_lote']) ?></td>
                            <td><?= $l['fecha_ingreso'] ?></td>
                            <td><?= $l['fecha_vencimiento'] ?></td>
                            <td><?= $l['cantidad_actual'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    — Sin lotes —
                <?php endif; ?>
            </td>
            <td style="font-weight:bold; background:#f8f9fa;"><?= $total_existencias ?></td>
        </tr>
        <?php endwhile; ?>

        <tr style="background:#f0f0f0;font-weight:bold;text-align:center;">
            <td colspan="7">TOTAL DE PRODUCTOS: <?= $result->num_rows ?></td>
            <td><?= $total_general_existencias ?></td>
        </tr>
    </table>
</main>

<?php include 'admin_footer.php'; ?>
