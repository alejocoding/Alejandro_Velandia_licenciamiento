<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
require_once('../../includes/validar_licencias.php');
$conexion = new database;
$con = $conexion->conectar();

if (!isset($_SESSION['cedula'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['borrar'])) {

    $licencia = $_POST['id_licencia'] ?? '';
    $empresa = $_POST['id_empresa'] ?? '';

    if (empty($licencia) || empty($empresa)) {
        echo "<script>alert('Error, no se puede eliminar la licencia'); window.location.href = 'index.php'</script>";
        exit();
    }


    $borrar = $con->prepare("DELETE FROM licencias WHERE id_empresa =  ? and id_licencia = ?");
    $borrar->execute([$empresa, $licencia]);
    echo "<script>alert('licencia Eliminada Con exito'); window.location.href = 'index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General super Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body class="bg-light">

    <header class="bg-white border-bottom shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <?php
            $total = $con->prepare("SELECT sum(valor) FROM licencias");
            $total->execute();
            $total = $total->fetchColumn();
            ?>
            <div class="card bg-success text-white px-3 py-2">
                <strong>Total Ganado:</strong> $<?php echo $total ?>
            </div>
            <button class="btn btn-outline-danger" onclick="window.location.href='../../includes/sesion_destroy.php'">Cerrar sesión</button>
        </div>
    </header>

    <div class="container py-5">
        <div class="mb-4 text-center">
            <h1 class="mb-3">Bienvenido Mega Administrador, <?php echo $_SESSION['nombre']; ?></h1>
            <p class="lead">Desde aquí puedes crear empresas,licencias y asignarles administradores.</p>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-5 justify-content-center">
            <a href="create.php" class="btn btn-primary">Crear Empresa</a>
            <a href="createLicencias.php" class="btn btn-primary">Crear Licencias</a>
            <a href="empresasAdmin.php" class="btn btn-info text-white">Crear Administradores de Empresas</a>
        </div>

        <h3 class="mb-4">Licencias Activas</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>identificador</th>
                        <th>Tipo de Licencia</th>
                        <th>Empresa NIT</th>
                        <th>Empresa</th>
                        <th>valor</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $licencias = $con->prepare(
                        "SELECT licencias.id_licencia,tp.Nombre,sas.nombreEmpresa, licencias.valor,sas.id_empresa, licencias.fecha_inicio, licencias.fecha_fn, e.estado FROM licencias 
                        INNER JOIN empresa sas ON licencias.id_empresa = sas.id_empresa 
                        INNER JOIN tipolicencia tp ON tp.id_tipo_licencia = licencias.id_tipo_licencia
                        INNER JOIN estado e ON e.id_estado = licencias.id_estado WHERE licencias.id_estado = 1;"
                    );
                    $licencias->execute();
                    $licencias = $licencias->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($licencias as $licencia) { ?>
                        <tr>
                            <td><?php echo $licencia['id_licencia'] ?></td>
                            <td><?php echo $licencia['Nombre'] ?></td>
                            <td><?php echo $licencia['id_empresa'] ?></td>
                            <td><?php echo $licencia['nombreEmpresa'] ?></td>
                            <td>$<?php echo $licencia['valor'] ?></td>
                            <td><?php echo $licencia['fecha_inicio'] ?></td>
                            <td><?php echo $licencia['fecha_fn'] ?></td>
                            <td><?php echo $licencia['estado'] ?></td>
                            <td>
                                <div class="d-flex gap-2">

                                    <a href="editarLicencias.php?editar=<?php echo $licencia['id_licencia']; ?>" class="btn btn-sm btn-success">Editar</a>

                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="text" name="id_licencia" value="<?php echo $licencia['id_licencia'] ?>" hidden>
                                        <input type="text" name="id_empresa" value="<?php echo $licencia['id_empresa'] ?>" hidden>
                                        <button type="submit" onclick="return confirm('¿Deseas Eliminar Esta licencia? ')" class="btn btn-sm btn-danger" name="borrar">Borrar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>


</html>