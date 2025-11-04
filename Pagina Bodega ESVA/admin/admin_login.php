<?php
session_start();
require_once '../db.php'; // Conexión a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $clave = trim($_POST['clave']);

    // Buscar usuario con rol Administrador
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND rol = 'Administrador' AND estado = 'Activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Si las contraseñas están en texto plano, usa comparación directa
        // ⚠️ Recomendado: usar MD5 o password_hash en la BD
        if ($usuario['contrasena'] === md5($clave) || $usuario['contrasena'] === $clave) {
            $_SESSION['admin'] = $usuario['correo'];
            header("Location: index.php"); // Redirigir al dashboard
            exit;
        } else {
            $error = "❌ Contraseña incorrecta.";
        }
    } else {
        $error = "⚠️ Usuario no encontrado o sin permisos de administrador.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Bodega ESVA</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e1e2f, #4a4a6a);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }
        .login-box {
            max-width: 400px;
            margin: 120px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #1e1e2f;
        }
        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: #1e1e2f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-box button:hover {
            background: #343456;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔑 Acceso Administrador</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
