<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubicación - Bodega ESVA</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f4f6f9, #e9ecef);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            color: #4e73df;
            text-align: center;
            font-size: 2em;
            margin-top: 40px;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        p {
            line-height: 1.6;
        }

        iframe {
            display: block;
            margin: 20px auto;
            width: 90%;
            height: 450px;
            border: 0;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Contenedores de info */
        .info-box {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px 25px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .info-box h3 {
            color: #4e73df;
            margin-bottom: 10px;
            text-align: center;
        }

        .info-box p {
            text-align: center;
            font-size: 1.05em;
            color: #555;
            margin: 8px 0;
        }

        .info-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 10px 50px 10px;
        }

        @media (max-width: 768px) {
            iframe {
                height: 300px;
            }

            h2 {
                font-size: 1.7em;
            }

            .info-box p {
                font-size: 0.95em;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <h2>📍 Ubicación de Bodega ESVA</h2>
    <p style="text-align:center; max-width:800px; margin: 0 auto 20px auto;">
        En Bodega ESVA nos apasiona ofrecer productos de calidad y un servicio confiable. Nuestra sede principal se encuentra estratégicamente ubicada para facilitar la distribución de todos nuestros productos a nivel nacional. 
        Visítanos y conoce nuestras instalaciones.
    </p>

    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.878759246352!2d-77.04279338458197!3d-12.046373045004955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8a6a2f6e7e3%3A0x1234567890abcdef!2sBodega%20ESVA!5e0!3m2!1ses!2spe!4v1696969696969" 
        allowfullscreen="" loading="lazy">
    </iframe>

    <div class="info-container">
        <div class="info-box">
            <h3>📌 Dirección</h3>
            <p>Av. Principal 123, Lima, Perú</p>
        </div>
        <div class="info-box">
            <h3>🕒 Horario de Atención</h3>
            <p>Lunes a Viernes: 08:00 - 18:00</p>
            <p>Sábado: 09:00 - 14:00</p>
        </div>
        <div class="info-box">
            <h3>📞 Contacto</h3>
            <p>Teléfono: (01) 234-5678</p>
            <p>Correo: contacto@bodegaesva.com</p>
        </div>
        <div class="info-box">
            <h3>🎯 Filosofía</h3>
            <p>Comprometidos con nuestros clientes, garantizando una experiencia de compra segura, eficiente y confiable.</p>
        </div>
    </div>

</body>
</html>
