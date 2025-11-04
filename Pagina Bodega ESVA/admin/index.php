<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
require_once '../db.php';

// ==========================================================
// 🔢 Contadores dinámicos
// ==========================================================
$total_productos     = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$total_presentaciones = $conn->query("SELECT COUNT(*) AS total FROM presentaciones_producto")->fetch_assoc()['total'];
$total_categorias    = $conn->query("SELECT COUNT(*) AS total FROM categorias")->fetch_assoc()['total'];
$total_proveedores   = $conn->query("SELECT COUNT(*) AS total FROM proveedores")->fetch_assoc()['total'];
$total_empleados     = $conn->query("SELECT COUNT(*) AS total FROM empleados")->fetch_assoc()['total'];
$total_clientes      = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='Cliente'")->fetch_assoc()['total'];
$total_pedidos       = $conn->query("SELECT COUNT(*) AS total FROM pedidos WHERE estado IN ('Pendiente','Pagado')")->fetch_assoc()['total'];
$total_compras       = $conn->query("SELECT COUNT(*) AS total FROM compras")->fetch_assoc()['total'];
$total_lotes         = $conn->query("SELECT COUNT(*) AS total FROM lotes")->fetch_assoc()['total'];
$total_stock_general = $conn->query("SELECT SUM(stock) AS total_stock FROM presentaciones_producto")->fetch_assoc()['total_stock'] ?? 0;

// ==========================================================
// 📊 Datos para gráficos
// ==========================================================

// 1️⃣ Productos por categoría
$categorias = [];
$totales_categoria = [];
$resCat = $conn->query("
    SELECT c.nombre_categoria, COUNT(p.id_producto) AS total
    FROM categorias c
    LEFT JOIN productos p ON c.id_categoria = p.id_categoria
    GROUP BY c.id_categoria
    ORDER BY total DESC
");
while ($row = $resCat->fetch_assoc()) {
    $categorias[] = $row['nombre_categoria'];
    $totales_categoria[] = $row['total'];
}

// 2️⃣ Stock total por categoría
$stock_categoria_labels = [];
$stock_categoria_totales = [];
$resStockCat = $conn->query("
    SELECT c.nombre_categoria, SUM(pp.stock) AS total_stock
    FROM categorias c
    LEFT JOIN productos p ON c.id_categoria = p.id_categoria
    LEFT JOIN presentaciones_producto pp ON pp.id_producto = p.id_producto
    GROUP BY c.id_categoria
");
while ($row = $resStockCat->fetch_assoc()) {
    $stock_categoria_labels[] = $row['nombre_categoria'];
    $stock_categoria_totales[] = $row['total_stock'] ?? 0;
}

// 3️⃣ Productos por proveedor (pastel)
$proveedores_labels = [];
$proveedores_totales = [];
$resProv = $conn->query("
    SELECT pr.razon_social, COUNT(p.id_producto) AS total
    FROM proveedores pr
    LEFT JOIN productos p ON p.id_proveedor = pr.id_proveedor
    GROUP BY pr.id_proveedor
");
while ($row = $resProv->fetch_assoc()) {
    $proveedores_labels[] = $row['razon_social'] ?? '—';
    $proveedores_totales[] = $row['total'];
}

// 4️⃣ Pedidos activos vs completados (pastel)
$pedidos_labels = ['Pendiente/Pagado', 'Entregado/Cancelado'];
$pedidos_totales = [];
$pedidos_totales[] = $conn->query("SELECT COUNT(*) FROM pedidos WHERE estado IN ('Pendiente','Pagado')")->fetch_row()[0];
$pedidos_totales[] = $conn->query("SELECT COUNT(*) FROM pedidos WHERE estado IN ('Entregado','Cancelado')")->fetch_row()[0];

?>

<?php include 'admin_header.php'; ?>
<?php include 'admin_navbar.php'; ?>

<main style="padding: 20px; background:#f4f6f9; min-height: 100vh;">
    <h1 style="margin-bottom:10px;">📊 Panel de Administración</h1>
    <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['admin']['nombre_usuario'] ?? 'Administrador') ?></strong>, al sistema de gestión de la <strong>Bodega ESVA</strong>.</p>

    <!-- TARJETAS RESUMEN -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px; margin-top:30px;">
        <?php
        $cards = [
            ["📦 Productos", $total_productos, "#4e73df,#224abe"],
            ["🎚 Presentaciones", $total_presentaciones, "#6f42c1,#4b0082"],
            ["🗂 Categorías", $total_categorias, "#1cc88a,#13855c"],
            ["🧾 Compras", $total_compras, "#f6c23e,#dda20a"],
            ["🛒 Pedidos Activos", $total_pedidos, "#36b9cc,#117a8b"],
            ["👔 Empleados", $total_empleados, "#858796,#3a3b45"],
            ["🏭 Proveedores", $total_proveedores, "#e74a3b,#be2617"],
            ["🧮 Lotes Registrados", $total_lotes, "#20c997,#0f5132"],
            ["📊 Stock Total", number_format($total_stock_general), "#17a2b8,#004085"],
            ["👥 Clientes", $total_clientes, "#6610f2,#240046"]
        ];
        foreach ($cards as $c): ?>
        <div style="
            background:linear-gradient(135deg,<?= $c[2] ?>);
            color:white; padding:20px; border-radius:15px;
            box-shadow:0 4px 8px rgba(0,0,0,0.2);
            text-align:center;
        ">
            <h3 style="color:white;"><?= $c[0] ?></h3>
            <p style="font-size:2em;font-weight:bold;color:white;"><?= $c[1] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- GRÁFICOS RESPONSIVOS -->
    <section style="margin-top:50px; display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:25px;">
        <div style="background:white; padding:15px; border-radius:12px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
            <h3>📊 Productos por Categoría</h3>
            <canvas id="chartProductosCategoria" style="height:250px;"></canvas>
        </div>

        <div style="background:white; padding:15px; border-radius:12px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
            <h3>📦 Stock por Categoría</h3>
            <canvas id="chartStockCategoria" style="height:250px;"></canvas>
        </div>

        <div style="background:white; padding:15px; border-radius:12px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
            <h3>🏭 Productos por Proveedor</h3>
            <canvas id="chartProductosProveedor" style="height:250px;"></canvas>
        </div>

        <div style="background:white; padding:15px; border-radius:12px; box-shadow:0 3px 6px rgba(0,0,0,0.1);">
            <h3>🛒 Pedidos Activos vs Completados</h3>
            <canvas id="chartPedidos" style="height:250px;"></canvas>
        </div>
    </section>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Productos por categoría (barra)
new Chart(document.getElementById('chartProductosCategoria'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($categorias) ?>,
        datasets: [{
            label: 'Cantidad de productos',
            data: <?= json_encode($totales_categoria) ?>,
            backgroundColor: '#4e73df',
            borderRadius: 5
        }]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

// Stock por categoría (barra)
new Chart(document.getElementById('chartStockCategoria'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($stock_categoria_labels) ?>,
        datasets: [{
            label: 'Stock total',
            data: <?= json_encode($stock_categoria_totales) ?>,
            backgroundColor: '#36b9cc',
            borderRadius: 5
        }]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

// Productos por proveedor (pastel)
new Chart(document.getElementById('chartProductosProveedor'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($proveedores_labels) ?>,
        datasets: [{
            data: <?= json_encode($proveedores_totales) ?>,
            backgroundColor: [
                '#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#6610f2','#20c997','#6f42c1','#fd7e14'
            ]
        }]
    },
    options: { responsive:true }
});

// Pedidos activos vs completados (pastel)
new Chart(document.getElementById('chartPedidos'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($pedidos_labels) ?>,
        datasets: [{
            data: <?= json_encode($pedidos_totales) ?>,
            backgroundColor: ['#f6c23e','#1cc88a']
        }]
    },
    options: { responsive:true }
});
</script>

<?php include 'admin_footer.php'; ?>
