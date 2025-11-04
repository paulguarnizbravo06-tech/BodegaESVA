<?php 
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../db.php';

// ==========================
// 🔁 Actualizar estado pedido
// ==========================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $sql_update = "UPDATE pedidos SET estado = ? WHERE id_pedido = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $nuevo_estado, $id_pedido);

    if ($stmt->execute()) {
        $mensaje = "✅ Estado del pedido actualizado correctamente.";
    } else {
        $error = "❌ Error al actualizar el estado del pedido.";
    }
}

// ==========================
// 📦 Listar pedidos
// ==========================
$sql = "SELECT 
            p.id_pedido, 
            u.nombre_usuario AS cliente, 
            p.fecha_pedido,
            p.total, 
            p.estado, 
            p.metodo_pago,
            p.direccion_entrega
        FROM pedidos p
        LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
        ORDER BY p.id_pedido DESC";
$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main style="padding: 20px;">
    <h2>🛒 Gestión de Pedidos</h2>

    <?php if(isset($mensaje)): ?>
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin:10px 0;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin:10px 0;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <table border="0" cellpadding="10" style="border-collapse: collapse; width: 100%; background:#fff; box-shadow:0 0 5px rgba(0,0,0,0.1);">
        <tr style="background:#1e1e2f; color:white;">
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total (S/)</th>
            <th>Método de Pago</th>
            <th>Estado</th>
            <th>Dirección de Entrega</th>
            <th>Acciones</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr style="text-align:center; border-bottom:1px solid #ddd;">
            <td><?= $row['id_pedido'] ?></td>
            <td><?= htmlspecialchars($row['cliente'] ?? 'Cliente eliminado') ?></td>
            <td><?= $row['fecha_pedido'] ?></td>
            <td><?= number_format($row['total'], 2) ?></td>
            <td><?= htmlspecialchars($row['metodo_pago']) ?></td>
            <td>
                <span style="
                    padding:5px 10px;
                    border-radius:5px;
                    color:white;
                    background:
                        <?= $row['estado']=='Pendiente' ? '#ffc107' : 
                            ($row['estado']=='Pagado' ? '#007bff' : 
                            ($row['estado']=='Entregado' ? '#28a745' : '#dc3545')); ?>">
                    <?= $row['estado'] ?>
                </span>
            </td>
            <td><?= htmlspecialchars($row['direccion_entrega'] ?? '-') ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_pedido" value="<?= $row['id_pedido'] ?>">
                    <select name="nuevo_estado" required style="padding:4px;border-radius:4px;">
                        <option value="">--Estado--</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Pagado">Pagado</option>
                        <option value="Entregado">Entregado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                    <button type="submit" name="actualizar_estado" 
                            style="background:#1e1e2f;color:white;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;">
                        🔄 Actualizar
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include 'admin_footer.php'; ?>
