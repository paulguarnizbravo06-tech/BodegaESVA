<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<!-- Enlace al archivo CSS principal -->
<link rel="stylesheet" href="estilos.css">

<main class="contenedor-principal">
    <section class="bienvenida">
        <h1>Bienvenido a <span class="resaltado">Bodega ESVA</span> 🛒</h1>
        <p>Disfruta de nuestros productos frescos y de calidad, al mejor precio.</p>
    </section>

    <section class="banner-promocional">
        <div class="slider">
            <div class="slide activo">
                <img src="assets/img/banner1.png" alt="Promoción 1">
                <div class="texto-banner">
                    <h2>2x1 en vinos TUYO 🍷</h2>
                    <p>TUYO Borgoña y Rosé de 310 ml a solo <strong>S/ 13.50</strong></p>
                </div>
            </div>
            <div class="slide">
                <img src="assets/img/banner2.png" alt="Promoción 2">
                <div class="texto-banner">
                    <h2>Piscos peruanos al mejor precio 🇵🇪</h2>
                    <p>Aprovecha nuestras promociones de temporada.</p>
                </div>
            </div>
            <div class="slide">
                <img src="assets/img/banner3.png" alt="Promoción 3">
                <div class="texto-banner">
                    <h2>¡Cyber 365 en Bodega ESVA! ⚡</h2>
                    <p>Descuentos especiales todo el año.</p>
                </div>
            </div>
        </div>

        <div class="controles">
            <span class="punto activo"></span>
            <span class="punto"></span>
            <span class="punto"></span>
        </div>
    </section>

     <section class="categorias">
        <h2>CATEGORÍAS</h2>
        <div class="contenedor-categorias">
            <button class="btn-categoria anterior">❮</button>
            <div class="lista-categorias">
                <?php
                require_once 'db.php'; // Conexión a la BD
                $queryCat = $conn->query("SELECT * FROM categorias ORDER BY nombre_categoria ASC");
                while($cat = $queryCat->fetch_assoc()):
                ?>
                    <div class="categoria">
                        <img src="assets/img/categorias/<?= strtolower($cat['nombre_categoria']) ?>.png" alt="<?= htmlspecialchars($cat['nombre_categoria']) ?>">
                        <p><?= htmlspecialchars(strtoupper($cat['nombre_categoria'])) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
            <button class="btn-categoria siguiente">❯</button>
        </div>
    </section>


   <section class="seccion-productos">
        <h2>Productos destacados</h2>
        <p>Encuentra lo mejor de nuestra bodega:</p>

        <div class="contenedor-productos">
            <?php
            $queryProd = $conn->query("
                SELECT p.id_producto, p.nombre_producto, p.descripcion, p.imagen, pp.precio_venta
                FROM productos p
                LEFT JOIN presentaciones_producto pp ON pp.id_producto = p.id_producto
                GROUP BY p.id_producto
                LIMIT 8
            ");
            while($prod = $queryProd->fetch_assoc()):
            ?>
            <article class="tarjeta-producto">
                <img src="assets/img/productos/<?= htmlspecialchars($prod['imagen'] ?: 'default.png') ?>" 
                     alt="<?= htmlspecialchars($prod['nombre_producto']) ?>" class="imagen-producto">
                <div class="detalle-producto">
                    <h3><?= htmlspecialchars($prod['nombre_producto']) ?></h3>
                    <p><?= htmlspecialchars($prod['descripcion']) ?></p>
                    <p class="precio"><strong>S/ <?= number_format($prod['precio_venta'],2) ?></strong></p>
                    <button class="btn-agregar">Añadir al carrito 🛍️</button>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<script>
    let indice = 0;
    const slides = document.querySelectorAll('.slide');
    const puntos = document.querySelectorAll('.punto');

    function mostrarSlide(nuevoIndice) {
        slides.forEach((slide, i) => {
            slide.style.transform = `translateX(-${nuevoIndice * 100}%)`;
            puntos[i].classList.toggle('activo', i === nuevoIndice);
        });
        indice = nuevoIndice;
    }

    // Avanzar automáticamente cada 5 segundos
    setInterval(() => {
        let siguiente = (indice + 1) % slides.length;
        mostrarSlide(siguiente);
    }, 5000);

    // Cambiar manualmente con los puntos
    puntos.forEach((punto, i) => {
        punto.addEventListener('click', () => mostrarSlide(i));
    });

    // === Carrusel de Categorías ===
    const lista = document.querySelector('.lista-categorias');
    const btnAnterior = document.querySelector('.anterior');
    const btnSiguiente = document.querySelector('.siguiente');

    let desplazamiento = 0;
    const paso = 200; // cantidad de desplazamiento por clic

    btnSiguiente.addEventListener('click', () => {
        if (Math.abs(desplazamiento) < (lista.scrollWidth - lista.clientWidth)) {
            desplazamiento -= paso;
            lista.style.transform = `translateX(${desplazamiento}px)`;
        }
    });

    btnAnterior.addEventListener('click', () => {
        if (desplazamiento < 0) {
            desplazamiento += paso;
            lista.style.transform = `translateX(${desplazamiento}px)`;
        }
    });
</script>

<?php include 'footer.php'; ?>