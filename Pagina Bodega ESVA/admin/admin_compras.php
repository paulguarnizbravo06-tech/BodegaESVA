<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';
include 'admin_header.php';
include 'admin_navbar.php';
?>

<main>
    <h2>🧾 Registrar Compra / Ingreso de Productos</h2>

    <form id="formCompra" method="POST" action="registrar_compra.php" enctype="multipart/form-data">
        <label>🧍‍♂️ Proveedor:</label>
        <select name="id_proveedor" required>
            <option value="">Seleccione proveedor</option>
            <?php
            $prov = $conn->query("SELECT id_proveedor, razon_social FROM proveedores ORDER BY razon_social");
            while ($p = $prov->fetch_assoc()) {
                echo "<option value='{$p['id_proveedor']}'>{$p['razon_social']}</option>";
            }
            ?>
        </select>

        <label>👷‍♂️ Empleado:</label>
        <select name="id_empleado" required>
            <option value="">Seleccione empleado</option>
            <?php
            $emp = $conn->query("SELECT id_empleado, nombres, apellidos FROM empleados");
            while ($e = $emp->fetch_assoc()) {
                echo "<option value='{$e['id_empleado']}'>{$e['nombres']} {$e['apellidos']}</option>";
            }
            ?>
        </select>

        <label>📌 Estado de la compra:</label>
        <select name="estado" required>
            <option value="Registrada">Registrada</option>
            <option value="Pagada">Pagada</option>
            <option value="Anulada">Anulada</option>
        </select>

        <hr>

        <h3>📦 Agregar productos</h3>
        <table id="tablaProductos" border="1" cellpadding="5" style="border-collapse:collapse;width:100%;">
            <thead style="background:#1e1e2f;color:white;">
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Presentación</th>
                    <th>Cant. contenida</th>
                    <th>Unidad medida</th>
                    <th>Precio compra</th>
                    <th>Cantidad</th>
                    <th>Fecha vencimiento</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="button" onclick="agregarFila()">➕ Agregar Producto</button>

        <h3 style="text-align:right;">Total: S/. <span id="total">0.00</span></h3>

        <button type="submit" style="background:#28a745;color:white;padding:10px 20px;border:none;border-radius:6px;">
            💾 Registrar Compra
        </button>
    </form>
</main>

<script>
function agregarFila() {
    const fila = `
        <tr>
            <td><input type="text" name="nombre_producto[]" placeholder="Nombre producto" required></td>
            <td>
                <select name="id_categoria[]" required>
                    <option value="">Seleccione</option>
                    <?php
                    $cats = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias");
                    while ($c = $cats->fetch_assoc()) {
                        echo "<option value='{$c['id_categoria']}'>{$c['nombre_categoria']}</option>";
                    }
                    ?>
                </select>
            </td>
            <td><textarea name="descripcion[]" placeholder="Descripción del producto"></textarea></td>
            <td><input type="file" name="imagen[]" accept="image/*"></td>
            <td><input type="text" name="nombre_presentacion[]" placeholder="Ej: Caja, Unidad" required></td>
            <td><input type="number" name="cantidad_contenida[]" min="1" value="1" required></td>
            <td><input type="text" name="unidad_medida[]" placeholder="unidad, ml, g, etc." required></td>
            <td><input type="number" name="precio_compra[]" step="0.01" min="0" onchange="calcularTotal()" required></td>
            <td><input type="number" name="cantidad[]" min="1" value="1" onchange="calcularTotal()" required></td>
            <td><input type="date" name="fecha_vencimiento[]" required></td>
            <td class="subtotal">0.00</td>
            <td><button type="button" onclick="this.closest('tr').remove();calcularTotal();">❌</button></td>
        </tr>`;
    document.querySelector("#tablaProductos tbody").insertAdjacentHTML('beforeend', fila);
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('#tablaProductos tbody tr').forEach(fila => {
        const precio = parseFloat(fila.querySelector('[name="precio_compra[]"]').value) || 0;
        const cantidad = parseInt(fila.querySelector('[name="cantidad[]"]').value) || 0;
        const subtotal = precio * cantidad;
        fila.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total').textContent = total.toFixed(2);
}
</script>

<?php include 'admin_footer.php'; ?>
