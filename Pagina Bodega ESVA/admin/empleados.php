<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

// ✅ Registrar nuevo empleado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $cargo = trim($_POST['cargo']);
    $fecha_ingreso = $_POST['fecha_ingreso'];

    if (!empty($nombres) && !empty($apellidos) && !empty($dni) && !empty($cargo)) {
        $sql = "INSERT INTO empleados (nombres, apellidos, dni, telefono, correo, cargo, fecha_ingreso)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $telefono, $correo, $cargo, $fecha_ingreso);

        if ($stmt->execute()) {
            $mensaje = "✅ Empleado agregado correctamente.";
        } else {
            $error = "❌ Error al registrar empleado (posible DNI o correo duplicado).";
        }
    } else {
        $error = "⚠️ Los campos obligatorios no pueden estar vacíos.";
    }
}

// ✅ Consultar empleados existentes
$sql = "SELECT * FROM empleados ORDER BY id_empleado DESC";
$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main>
    <h2>👔 Gestión de Empleados</h2>

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

    <!-- 🧾 Formulario para agregar empleado -->
    <form method="POST" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 6px rgba(0,0,0,0.1);margin-bottom:20px;">
        <h3>➕ Nuevo Empleado</h3>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;">
            <input type="text" name="nombres" placeholder="Nombres" required style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="text" name="apellidos" placeholder="Apellidos" required style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="text" name="dni" placeholder="DNI (8 dígitos)" pattern="[0-9]{8}" required style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="text" name="telefono" placeholder="Teléfono" style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="email" name="correo" placeholder="Correo electrónico" style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="text" name="cargo" placeholder="Cargo" required style="padding:10px;border:1px solid #ccc;border-radius:5px;">
            <input type="date" name="fecha_ingreso" value="<?= date('Y-m-d') ?>" style="padding:10px;border:1px solid #ccc;border-radius:5px;">
        </div>

        <button type="submit" name="agregar" 
                style="margin-top:15px;padding:10px 15px;background:#1e1e2f;color:white;border:none;border-radius:5px;cursor:pointer;">
            Guardar Empleado
        </button>
    </form>

    <!-- 📋 Tabla de empleados -->
    <h3>📋 Lista de Empleados</h3>
    <table border="0" cellpadding="10" style="border-collapse: collapse; width:100%; background:#fff; box-shadow:0 0 5px rgba(0,0,0,0.1);">
        <tr style="background:#1e1e2f;color:white;">
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Cargo</th>
            <th>Fecha Ingreso</th>
            <th>Acciones</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr style="text-align:center;border-bottom:1px solid #ddd;">
            <td><?= $row['id_empleado'] ?></td>
            <td><?= htmlspecialchars($row['nombres']) ?></td>
            <td><?= htmlspecialchars($row['apellidos']) ?></td>
            <td><?= htmlspecialchars($row['dni']) ?></td>
            <td><?= htmlspecialchars($row['telefono']) ?></td>
            <td><?= htmlspecialchars($row['correo']) ?></td>
            <td><?= htmlspecialchars($row['cargo']) ?></td>
            <td><?= htmlspecialchars($row['fecha_ingreso']) ?></td>
            <td>
                <button style="padding:5px 10px; background:#ffc107; border:none; border-radius:4px; cursor:pointer;">✏️</button>
                <button style="padding:5px 10px; background:#dc3545; color:white; border:none; border-radius:4px; cursor:pointer;">🗑</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include 'admin_footer.php'; ?>
