<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $correo = trim($_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre_usuario, $correo, $contrasena, $rol);

    if ($stmt->execute()) {
        header("Location: admin_usuarios.php?msg=success");
    } else {
        echo "❌ Error al registrar usuario interno.";
    }
}
?>
