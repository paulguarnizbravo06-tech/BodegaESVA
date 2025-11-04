
<?php
include 'header.php';
include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    // Crear el texto a guardar
    $registro = "Nombre: $nombre | Correo: $correo | Sugerencia: $mensaje | Fecha: " . date("Y-m-d H:i:s") . "\n";

    // Guardar en archivo (modo append para no sobrescribir)
    file_put_contents("sugerencias.txt", $registro, FILE_APPEND);

    $success = "¡Gracias por tu sugerencia! (Se guardó en archivo)";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sugerencias - Bodega ESVA</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Formulario de Sugerencias</h2>
    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Tu nombre" required><br>
        <input type="email" name="correo" placeholder="Tu correo" required><br>
        <textarea name="mensaje" placeholder="Escribe tu sugerencia..." required></textarea><br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
