<!-- Asegúrate de tener Font Awesome en tu <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #222; color: white;">
    <!-- Logo y título -->
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="assets/img/logo.png" alt="Logo Bodega ESVA" style="height: 50px;">
        <h2>Bodega ESVA</h2>
    </div>

    <!-- Menú -->
    <ul style="list-style: none; display: flex; gap: 15px; margin: 0; padding: 0;">
        <li><a href="index.php" style="color: white; text-decoration: none;">Inicio</a></li>
        <li><a href="sugerencias.php" style="color: white; text-decoration: none;">Sugerencias</a></li>
        <li><a href="reclamaciones.php" style="color: white; text-decoration: none;">Reclamaciones</a></li>
        <li><a href="ubicacion.php" style="color: white; text-decoration: none;">Ubicación</a></li>
        <li><a href="videos.php" style="color: white; text-decoration: none;">Videos</a></li>
        <li><a href="perfil.php" style="color: white; text-decoration: none;">Mi Perfil</a></li>
        <li><a href="login.php" style="color: white; text-decoration: none;">Iniciar Sesión</a></li>
    </ul>

    <!-- Carrito al lado derecho -->
    <div>
        <a href="carrito.php" style="font-size: 22px; color: white; text-decoration: none; position: relative;">
            <i class="fa-solid fa-cart-shopping"></i>
            <!-- 🔔 Badge (contador opcional) -->
            <span style="position: absolute; top: -8px; right: -10px; background: red; color: white; font-size: 12px; padding: 2px 6px; border-radius: 50%;">3</span>
        </a>
    </div>
</nav>
<hr>
