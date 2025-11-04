<?php
include 'header.php';
include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $correo = $_POST['correo'];
    $detalle = $_POST['detalle'];

    // Crear el texto para guardar
    $registro = "Nombre: $nombre | DNI: $dni | Correo: $correo | Reclamo: $detalle | Fecha: " . date("Y-m-d H:i:s") . "\n";

    // Guardar en un archivo (modo append para no sobrescribir)
    file_put_contents("reclamaciones.txt", $registro, FILE_APPEND);

    $success = "Su reclamo ha sido registrado correctamente (guardado en archivo).";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro de Reclamaciones - Bodega ESVA</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h2>Libro de Reclamaciones</h2>
    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Tu nombre completo" required><br>
        <input type="text" name="dni" placeholder="DNI" required><br>
        <input type="email" name="correo" placeholder="Tu correo" required><br>
        <textarea name="detalle" placeholder="Detalle de tu reclamo..." required></textarea><br>
        <button type="submit">Registrar Reclamo</button>
    </form>
</body>
</html>
