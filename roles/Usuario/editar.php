<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['editarUsuario'])) {
    $cedulaAntigua = $_SESSION['cedula'];
    $cedulaNueva = $_POST['cedula'];
    $nombreNuevo = $_POST['nombre'];
    $Telefono = $_POST['Telefono'];
    $correo = $_POST['Correo']; // Corregido

    if (empty($cedulaAntigua) || empty($cedulaNueva) || empty($nombreNuevo) || empty($Telefono) || empty($correo)) {
        echo "<script>alert('Datos vacíos'); window.location.href = 'editar.php'</script>";
        exit();
    }

    $consulta = $con->prepare("UPDATE usuario SET cedula = ?, nombreCompleto = ?, Telefono = ?, Correo = ? WHERE cedula = ?");
    if ($consulta->execute([$cedulaNueva, $nombreNuevo, $Telefono, $correo, $cedulaAntigua])) {
        $_SESSION['cedula'] = $cedulaNueva; // actualiza la cédula en sesión si cambió
        echo "<script>alert('Registro actualizado correctamente');</script>";
    } else {
        echo "<script>alert('Registro no actualizado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil-square me-2"></i>Editar Usuario</h2>
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle me-1"></i>Volver</a>
        </div>

        <?php
        $cedula = $_SESSION['cedula'];
        $formulario = $con->prepare("SELECT * FROM usuario WHERE cedula = ?");
        $formulario->execute([$cedula]);
        $formulario = $formulario->fetchAll();

        foreach ($formulario as $user) { ?>
            <form action="" method="POST" class="card p-4 shadow-sm bg-white">
                <div class="mb-3">
                    <label class="form-label">Cédula</label>
                    <input type="number" name="cedula" class="form-control" value="<?php echo $user['cedula'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo $user['nombreCompleto'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="Telefono" class="form-control" value="<?php echo $user['Telefono'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="Correo" class="form-control" value="<?php echo $user['Correo']; ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="editarUsuario" class="btn btn-primary">
                        <i class="bi bi-save2 me-1"></i>Guardar Cambios
                    </button>
                   
                </div>
            </form>
        <?php } ?>
    </div>

</body>

</html>
