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
    <title>Licencia Activa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Licencia Activa</h2>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center bg-white shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo De Licencia</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Días Restantes</th>
                        <th>Usos Restantes</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = $con->prepare("SELECT * FROM licencias 
                        INNER JOIN tipolicencia ON tipolicencia.id_tipo_licencia = licencias.id_tipo_licencia
                        INNER JOIN estado ON estado.id_estado = licencias.id_estado 
                        WHERE id_empresa = ? and licencias.id_estado = 1");
                    $consulta->execute([$_SESSION['id_empresa']]);
                    $consulta = $consulta->fetchAll();

                    foreach ($consulta as $licencia) { ?>
                        <tr>
                            <td><?php echo $licencia['Nombre']; ?></td>
                            <td class="fecha-inicio"><?php echo $licencia['fecha_inicio']; ?></td>
                            <td class="fecha-fin"><?php echo $licencia['fecha_fn']; ?></td>
                            <td class="dias-restantes">Calculando...</td>
                            <td class=""><?php echo $licencia['UsosGastados']; ?></td>
                            <td><?php echo $licencia['estado']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', () => {
            const filas = document.querySelectorAll('tbody tr');

            filas.forEach(fila => {
                const fechaFinTexto = fila.querySelector('.fecha-fin').textContent;
                const fechaInicioTexto = fila.querySelector('.fecha-inicio').textContent;
                const diasCelda = fila.querySelector('.dias-restantes');

                const fechaFin = new Date(fechaFinTexto);
                const fechaInicio = new Date(fechaInicioTexto);

                const diferencia = fechaFin - fechaInicio;
            
                const diasRestantes = Math.ceil(diferencia / (1000 * 60 * 60 * 24));

                if (diasRestantes >= 0) {
                    diasCelda.textContent = diasRestantes + " día(s)";
                } else {
                    diasCelda.innerHTML = '<span class="text-danger">Vencida</span>';
                }
            });
        });
    </script>
</body>

</html>