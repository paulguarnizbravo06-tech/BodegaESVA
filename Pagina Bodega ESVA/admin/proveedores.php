<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

// ✅ Agregar nuevo proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $razon_social = trim($_POST['razon_social']);
    $ruc = trim($_POST['ruc']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);

    if (!empty($razon_social)) {
        $sql = "INSERT INTO proveedores (razon_social, ruc, telefono, correo, direccion) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $razon_social, $ruc, $telefono, $correo, $direccion);
        if ($stmt->execute()) {
            $mensaje = "✅ Proveedor agregado correctamente.";
        } else {
            $error = "❌ Error al agregar el proveedor (verifique RUC duplicado).";
        }
    } else {
        $error = "⚠️ La razón social no puede estar vacía.";
    }
}

// ✅ Consultar todos los proveedores
$sql = "SELECT * FROM proveedores ORDER BY id_proveedor DESC";
$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main>
    <h2>🏭 Gestión de Proveedores</h2>

    <?php if(isset($mensaje)): ?>
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:10px;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:10px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- 🧾 Formulario para agregar nuevo proveedor -->
    <form method="POST" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 6px rgba(0,0,0,0.1);margin-bottom:20px;">
        <h3>➕ Nuevo Proveedor</h3>
        <input type="text" name="razon_social" placeholder="Razón Social" required
               style="padding:10px;width:100%;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">
        <input type="text" name="ruc" placeholder="RUC (11 dígitos)" maxlength="11"
               style="padding:10px;width:100%;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">
        <input type="text" name="telefono" placeholder="Teléfono"
               style="padding:10px;width:100%;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">
        <input type="email" name="correo" placeholder="Correo electrónico"
               style="padding:10px;width:100%;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">
        <textarea name="direccion" placeholder="Dirección"
                  style="padding:10px;width:100%;border:1px solid #ccc;border-radius:5px;margin-bottom:10px;"></textarea>
        <button type="submit" name="agregar"
                style="padding:10px 15px;background:#1e1e2f;color:white;border:none;border-radius:5px;cursor:pointer;">
            Guardar Proveedor
        </button>
    </form>

    <!-- 📋 Listado de proveedores -->
    <h3>📦 Proveedores Registrados</h3>
    <table border="0" cellpadding="10" style="border-collapse:collapse;width:100%;background:#fff;box-shadow:0 0 5px rgba(0,0,0,0.1);">
        <tr style="background:#1e1e2f;color:white;text-align:center;">
            <th>ID</th>
            <th>Razón Social</th>
            <th>RUC</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr style="text-align:center;border-bottom:1px solid #ddd;">
            <td><?= $row['id_proveedor'] ?></td>
            <td><?= htmlspecialchars($row['razon_social']) ?></td>
            <td><?= htmlspecialchars($row['ruc'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['telefono'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['correo'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['direccion'] ?? '') ?></td>
            <td>
                <button style="padding:5px 10px;background:#ffc107;border:none;border-radius:4px;cursor:pointer;">✏️ Editar</button>
                <button style="padding:5px 10px;background:#dc3545;color:white;border:none;border-radius:4px;cursor:pointer;">🗑 Eliminar</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include 'admin_footer.php'; ?>
