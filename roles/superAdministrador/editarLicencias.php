<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();


$datosLicencia = null;

if (isset($_GET['editar'])) {
    $idEditar = $_GET['editar'];

    $consulta = $con->prepare(
        "SELECT * FROM licencias WHERE id_licencia = ?"
    );
    $consulta->execute([$idEditar]);
    $datosLicencia = $consulta->fetch(PDO::FETCH_ASSOC);
}

// LOGICA PARA ACTUALIZAR LICENCIA

if (isset($_POST['actualizar'])) {
    $idLicencia = $_POST['id_licencia'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fn = $_POST['fecha_fn'] ?? '';

    // Validaciones básicas
    if (empty($idLicencia) || empty($valor) || empty($fecha_inicio) || empty($fecha_fn)) {
        echo "<script>alert('Error, campos vacíos'); window.location.href = 'index.php'</script>";
        exit();
    }

    if (strtotime($fecha_fn) < strtotime(date("Y-m-d"))) {
        echo "<script>alert('La fecha fin no puede ser menor a la fecha actual.'); window.location.href = 'index.php'</script>";
        exit();
    }

    $update = $con->prepare("UPDATE licencias SET valor = ?, fecha_inicio = ?, fecha_fn = ? WHERE id_licencia = ?");
    if ($update->execute([$valor, $fecha_inicio, $fecha_fn, $idLicencia])) {
        echo "<script>alert('Licencia actualizada correctamente'); window.location.href = 'index.php'</script>";
    } else {
        echo "<script>alert('Error al actualizar la licencia'); window.location.href = 'index.php'</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Licencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>

    <div class="card mb-4 shadow-sm">

        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-4">Volver</button>
        <div class="card-header bg-warning text-dark">
            <strong>Editar Licencia - <?php echo $datosLicencia['id_licencia']; ?></strong>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input type="hidden" name="id_licencia" value="<?php echo $datosLicencia['id_licencia']; ?>">

                <div class="mb-3">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="number" class="form-control" name="valor" value="<?php echo $datosLicencia['valor']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $datosLicencia['fecha_inicio']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fn" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fn" value="<?php echo $datosLicencia['fecha_fn']; ?>" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="actualizar" class="btn btn-success">Guardar Cambios</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>