<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<main>
    <h2>📦 Finalizar Compra</h2>
    <form method="post" action="checkout.php">
        <label>Dirección de entrega</label>
        <input type="text" name="direccion" required>

        <label>Método de pago</label>
        <select name="pago" required>
            <option value="tarjeta">Tarjeta</option>
            <option value="efectivo">Efectivo</option>
        </select>

        <button type="submit">Confirmar Pedido</button>
    </form>
</main>

<?php include 'footer.php'; ?>
