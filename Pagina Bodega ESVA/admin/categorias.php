<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

// ✅ Agregar nueva categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombre_categoria = trim($_POST['nombre_categoria']);
    $descripcion = trim($_POST['descripcion']);

    if (!empty($nombre_categoria)) {
        $sql = "INSERT INTO categorias (nombre_categoria, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre_categoria, $descripcion);
        if ($stmt->execute()) {
            $mensaje = "✅ Categoría agregada correctamente.";
        } else {
            $error = "❌ Error al agregar la categoría.";
        }
    } else {
        $error = "⚠️ El nombre de la categoría no puede estar vacío.";
    }
}

// ✅ Consultar todas las categorías
$sql = "SELECT * FROM categorias ORDER BY id_categoria DESC";
$result = $conn->query($sql);
?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main>
    <h2>🗂 Gestión de Categorías</h2>

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

    <!-- Formulario para agregar nueva categoría -->
    <form method="POST" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 6px rgba(0,0,0,0.1);margin-bottom:20px;">
        <h3>➕ Nueva Categoría</h3>
        <input type="text" name="nombre_categoria" placeholder="Nombre de la categoría" required 
               style="padding:10px;width:100%;margin-bottom:10px;border:1px solid #ccc;border-radius:5px;">
        <textarea name="descripcion" placeholder="Descripción (opcional)" 
                  style="padding:10px;width:100%;border:1px solid #ccc;border-radius:5px;margin-bottom:10px;"></textarea>
        <button type="submit" name="agregar"
                style="padding:10px 15px;background:#1e1e2f;color:white;border:none;border-radius:5px;cursor:pointer;">
            Guardar Categoría
        </button>
    </form>

    <!-- Listado de categorías -->
    <h3>📋 Categorías Registradas</h3>
    <table border="0" cellpadding="10" style="border-collapse: collapse; width:100%; background:#fff; box-shadow:0 0 5px rgba(0,0,0,0.1);">
        <tr style="background:#1e1e2f;color:white;">
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr style="text-align:center;border-bottom:1px solid #ddd;">
            <td><?= $row['id_categoria'] ?></td>
            <td><?= htmlspecialchars($row['nombre_categoria']) ?></td>
            <td><?= htmlspecialchars($row['descripcion'] ?? '') ?></td>
            <td>
                <button style="padding:5px 10px; background:#ffc107; border:none; border-radius:4px; cursor:pointer;">✏️ Editar</button>
                <button style="padding:5px 10px; background:#dc3545; color:white; border:none; border-radius:4px; cursor:pointer;">🗑 Eliminar</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include 'admin_footer.php'; ?>
