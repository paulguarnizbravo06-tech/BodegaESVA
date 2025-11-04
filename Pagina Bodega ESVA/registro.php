<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<main>
    <h2>📝 Registro de Usuario</h2>
    <form method="post" action="registro.php">
        <label>Nombre completo</label>
        <input type="text" name="nombre" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Registrarse</button>
    </form>
</main>

<?php include 'footer.php'; ?>
