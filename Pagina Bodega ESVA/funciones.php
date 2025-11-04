<?php
// Función para limpiar entradas del usuario
function limpiarDato($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Función para redirigir
function redirigir($url) {
    header("Location: $url");
    exit();
}

// Función para verificar si el usuario está logueado
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        redirigir("login.php");
    }
}
?>
