<nav class="sidebar">
    <div class="logo-section">
        <img src="../assets/img/logo.png" alt="Logo Bodega ESVA" class="logo">
        <h2>Bodega ESVA</h2>
        <p class="panel-title">Panel de Administración</p>
    </div>

    <ul class="menu">
        <li><a href="index.php"><span>📊</span> Dashboard</a></li>
        <li><a href="productos.php"><span>📦</span> Productos</a></li>
        <li><a href="categorias.php"><span>🗂</span> Categorías</a></li>
        <li><a href="pedidos.php"><span>🛒</span> Pedidos</a></li>

        <!-- ✅ NUEVO ENLACE A COMPRAS -->
        <li><a href="admin_compras.php"><span>🧾</span> Compras</a></li>

        <li><a href="proveedores.php"><span>🏭</span> Proveedores</a></li>
        <li><a href="empleados.php"><span>👔</span> Empleados</a></li>
        <li><a href="admin_users.php"><span>👥</span> Usuarios</a></li>

        <li><a href="../index.php"><span>🏬</span> Volver a Tienda</a></li>
        <li><a href="logout.php" class="logout"><span>🚪</span> Cerrar Sesión</a></li>
    </ul>
</nav>

<div class="main-content"></div>
<style>
.logo-section {
    text-align: center;
    padding: 25px 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.02);
}

.logo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #f8f9fa; /* borde más delgado */
    box-shadow: 0 0 6px rgba(255,255,255,0.15); /* sombra suave */
    background-color: white; /* mejora contraste */
    margin-bottom: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.logo:hover {
    transform: scale(1.04);
    box-shadow: 0 0 10px rgba(255,255,255,0.25);
}

.logo-section h2 {
    font-size: 19px;
    margin: 5px 0 0 0;
    color: #ffffff;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.panel-title {
    font-size: 13px;
    color: #b0b0b0;
    margin-top: 4px;
}
</style>
