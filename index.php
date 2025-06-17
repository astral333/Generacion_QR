<!-- filepath: c:\laragon\www\Generacion_QR\index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Certificados</title>
    <style>
        #loading {
            display: none;
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <form id="genForm" method="post">
        <button type="submit">Generar Certificados</button>
    </form>
    <div id="loading">
        <img src="loading.gif" alt="Cargando..." />
        <p>Procesando, por favor espere...</p>
    </div>
    <div id="result">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ...tu código PHP de generación aquí...
            require 'procesar.php'; // Mueve tu código de generación a procesar.php
        }
        ?>
    </div>
    <script>
        document.getElementById('genForm').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
</body>
</html>