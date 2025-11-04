<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<main>
    <h2>🛒 Carrito de Compras</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th>Acción</th>
        </tr>
        <!-- Aquí se cargarán los productos del carrito -->
        <tr>
            <td>Producto de ejemplo</td>
            <td>2</td>
            <td>S/10.00</td>
            <td>S/20.00</td>
            <td><button>Eliminar</button></td>
        </tr>
    </table>

    <p><strong>Total: S/20.00</strong></p>
    <a href="checkout.php"><button>Finalizar Compra</button></a>
</main>

<?php include 'footer.php'; ?>
