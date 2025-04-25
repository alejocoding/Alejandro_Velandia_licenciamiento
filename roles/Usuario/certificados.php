<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Certificado</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle me-1"></i>Volver
        </a>

        <h2 class="text-center mb-4">Buscar Certificado por Código</h2>

        <div class="mb-3">
            <form onsubmit="return false;">
                <label for="codigoBarras" class="form-label">Código del certificado:</label>
                <input type="text" class="form-control" id="codigoBarras" placeholder="Escriba o escanee el código" required>
            </form>
        </div>

        <div id="botonDescarga" class="text-center" style="display: none;">
            <a id="descargarPDF" href="#" class="btn btn-success" target="_blank">Descargar PDF</a>
        </div>

       
    </div>

    <script>
        const input = document.getElementById('codigoBarras');
        const boton = document.getElementById('botonDescarga');
        const enlace = document.getElementById('descargarPDF');
        const mensaje = document.getElementById('mensaje');

        input.addEventListener('input', () => {
            const codigo = input.value.trim();

            if (codigo.length >= 6) {
                fetch('verificar_certificado.php?codigo=' + codigo)
                    .then(res => res.json())
                    .then(data => {
                        if (data.existe) {
                            enlace.href = '../../certificados/' + data.ruta;
                            boton.style.display = 'block';
                            
                        } else {
                            boton.style.display = 'none';
                           
                        }
                    })
                    .catch(() => {
                        boton.style.display = 'none';
                       
                    });
            } else {
                boton.style.display = 'none';
             
            }
        });
    </script>
</body>

</html>