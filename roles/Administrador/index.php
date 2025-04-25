<?php
session_start();
require_once('../../includes/validacion_licencia_individual.php');
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');

$conexion = new database;
$con = $conexion->conectar();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <header class="bg-white border-bottom shadow-sm mb-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center py-3">
            <h5 class="mb-0">Bienvenido Administrador <span class="text-primary"><?php echo $_SESSION['nombre'] ?></span></h5>

          

            <?php
            $total = $con->prepare("SELECT COUNT(cedula) FROM usuario WHERE id_empresa = ? and id_role != 2");
            $total->execute([$_SESSION['id_empresa']]);
            $total = $total->fetchColumn();
            ?>
            <div class="card bg-success text-white px-3 py-2">
                <strong><i class="bi bi-people-fill me-2"></i>Usuarios registrados:</strong> <?php echo $total ?>
            </div>

            <button class="btn btn-outline-danger" onclick="window.location.href='../../includes/sesion_destroy.php'">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
            </button>
        </div>
    </header>

    <div class="container mb-5">
        <div class="d-grid gap-3">
            <button class="btn btn-primary" onclick="window.location.href='crearUsuarios.php'">
                <i class="bi bi-person-plus-fill me-2"></i> Agregar Usuarios
            </button>
            <button class="btn btn-secondary" onclick="window.location.href='licencia.php'">
                <i class="bi bi-card-checklist me-2"></i> Ver Licencia
            </button>
            <button class="btn btn-success" onclick="window.location.href='certificados.php'">
                <i class="bi bi-file-earmark-text-fill me-2"></i> Certificados Generados
            </button>
        </div>
    </div>

</body>

</html>

