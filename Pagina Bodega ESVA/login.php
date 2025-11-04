<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<main>
    <h2>🔐 Iniciar Sesión</h2>
    <form method="post" action="login.php">
        <label>Usuario o Email</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
</main>

<?php include 'footer.php'; ?>
