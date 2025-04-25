<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['borrar'])) {
    $certificado = $_POST['id_certificado'];
    $borrar = $con->prepare("DELETE FROM certificados WHERE id_certificado = ? AND propietario = ?");
    $borrar->execute([$certificado, $_SESSION['cedula']]);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Generar Certificado de Graduación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
    <a href="index.php" class="btn btn-secondary">Volver</a>
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Generar Certificado de Felicitación por Graduación</h2>
            <form action="generar_certificado.php" method="POST">
                <div class="mb-3">
                    <label for="nombrePersona" class="form-label">Nombre del graduado o graduada:</label>
                    <input type="text" class="form-control" id="nombrePersona" name="nombrePersona" required>
                </div>

                <div class="mb-3">
                    <label for="evento" class="form-label">Nombre del evento o ceremonia:</label>
                    <input type="text" class="form-control" id="evento" name="evento" value="Ceremonia de Graduación" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio_evento" class="form-label">Fecha de inicio del evento:</label>
                    <input type="date" class="form-control" id="fecha_inicio_evento" name="fecha_inicio_evento" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin_evento" class="form-label">Fecha de finalización del evento:</label>
                    <input type="date" class="form-control" id="fecha_fin_evento" name="fecha_fin_evento" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Generar Certificado</button>
            </form>
        </div>
    </div>

    <div class="container mt-5">
        <h3 class="mb-4">Certificados Generados</h3>
        <table class="table table-hover table-bordered table-striped">
            <thead class="table-success text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Evento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Propietario</th>
                    <th>Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $stmt = $con->prepare("SELECT *, u.nombreCompleto no FROM certificados 
                INNER JOIN usuario u ON u.cedula = certificados.Propietario WHERE Propietario = ? GROUP BY fecha_inicio_evento DESC");
                $stmt->execute([$_SESSION['cedula']]);
                $certificados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($certificados as $cert): ?>
                    <tr>
                        <td><?= ($cert['id_certificado']) ?></td>
                        <td><?= ($cert['nombrePersona']) ?></td>
                        <td><?= ($cert['evento']) ?></td>
                        <td><?= ($cert['fecha_inicio_evento']) ?></td>
                        <td><?= ($cert['fecha_fin_evento']) ?></td>
                        <td><?= ($cert['no']) ?></td>
                        <td><a class="btn btn-sm btn-primary" href="../../certificados/<?= ($cert['ruta_archivo']) ?>" target="_blank">Ver PDF</a></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('¿Deseas eliminar el certificado con identificador <?= $cert['id_certificado']; ?>?');">
                                <input type="hidden" name="id_certificado" value="<?= $cert['id_certificado']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" name="borrar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Validación simple
        document.querySelector("form").addEventListener("submit", function (e) {
            const nombrePersona = document.getElementById("nombrePersona").value.trim();
            const evento = document.getElementById("evento").value.trim();
            const fechaInicio = document.getElementById("fecha_inicio_evento").value;
            const fechaFin = document.getElementById("fecha_fin_evento").value;

            let errores = [];

            if (nombrePersona === "") {
                errores.push("El nombre del graduado o graduada no puede estar vacío.");
            }

            if (evento === "") {
                errores.push("El nombre del evento no puede estar vacío.");
            }

            if (!fechaInicio || !fechaFin) {
                errores.push("Ambas fechas deben estar completas.");
            } else if (fechaInicio > fechaFin) {
                errores.push("La fecha de inicio no puede ser posterior a la fecha de fin.");
            }

            if (errores.length > 0) {
                e.preventDefault();
                alert(errores.join("\n"));
            }
        });
    </script>
</body>

</html>
