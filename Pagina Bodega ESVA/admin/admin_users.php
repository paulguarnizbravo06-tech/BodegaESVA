<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../db.php';

// ============================
// ✅ CONSULTAS SQL
// ============================

// Usuarios internos (administradores y empleados)
$sql_internos = "SELECT id_usuario, nombre_usuario, correo, rol, fecha_registro 
                 FROM usuarios 
                 WHERE rol IN ('Administrador','Empleado')
                 ORDER BY id_usuario DESC";
$result_internos = $conn->query($sql_internos);

// Usuarios clientes
$sql_clientes = "SELECT id_usuario, nombre_usuario, correo, rol, fecha_registro 
                 FROM usuarios 
                 WHERE rol = 'Cliente'
                 ORDER BY id_usuario DESC";
$result_clientes = $conn->query($sql_clientes);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main>
    <h2>👥 Gestión de Usuarios</h2>

    <!-- FORMULARIO AGREGAR USUARIO INTERNO -->
    <section style="margin-bottom:25px;">
        <h3>➕ Agregar Usuario Interno</h3>
        <form action="admin_add_user.php" method="POST" style="margin-top:10px;">
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="">--Seleccionar rol--</option>
                <option value="Administrador">Administrador</option>
                <option value="Empleado">Empleado</option>
            </select>
            <button type="submit" 
                    style="padding:6px 12px; background:#28a745; color:white; border:none; border-radius:4px;">
                💾 Guardar
            </button>
        </form>
    </section>

    <!-- TABLA USUARIOS INTERNOS -->
    <section>
        <h3>👔 Usuarios Internos</h3>
        <table border="1" cellpadding="10" style="border-collapse: collapse; width:100%; margin-top:10px;">
            <tr style="background:#e9ecef;">
                <th>ID</th>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
            <?php while($row = $result_internos->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_usuario'] ?></td>
                <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= ucfirst($row['rol']) ?></td>
                <td><?= $row['fecha_registro'] ?></td>
                <td>
                    <a href="admin_edit_user.php?id=<?= $row['id_usuario'] ?>" 
                       style="padding:5px 8px; background:#ffc107; border:none; border-radius:4px; color:black; text-decoration:none;">✏️ Editar</a>
                    <a href="admin_delete_user.php?id=<?= $row['id_usuario'] ?>" 
                       onclick="return confirm('¿Seguro que deseas eliminar este usuario?');" 
                       style="padding:5px 8px; background:#dc3545; color:white; border:none; border-radius:4px; text-decoration:none;">🗑 Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- TABLA USUARIOS CLIENTES -->
    <section style="margin-top:30px;">
        <h3>🛒 Usuarios Clientes</h3>
        <table border="1" cellpadding="10" style="border-collapse: collapse; width:100%; margin-top:10px;">
            <tr style="background:#e9ecef;">
                <th>ID</th>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
            </tr>
            <?php while($row = $result_clientes->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_usuario'] ?></td>
                <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= ucfirst($row['rol']) ?></td>
                <td><?= $row['fecha_registro'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</main>

<?php include 'admin_footer.php'; ?>
