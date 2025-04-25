<?php
session_start();
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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h4 class="mb-4">Bienvenido <span class="text-primary"><?php echo $_SESSION['nombre'] ?></span></h4>

        <header class="bg-white border-bottom shadow-sm mb-4">
            <div class="container d-flex justify-content-between align-items-center py-3">
                <?php
                $total = $con->prepare("SELECT nombreEmpresa FROM empresa WHERE id_empresa = ?");
                $total->execute([$_SESSION['id_empresa']]);
                $total = $total->fetchColumn();
                ?>
                <div class="card bg-success text-white px-3 py-2">
                    <strong>Empresa:</strong> <?php echo $total ?>
                </div>
                <button class="btn btn-outline-danger" onclick="window.location.href='../../includes/sesion_destroy.php'">Cerrar sesión</button>
            </div>
        </header>

        <div class="text-center mb-5">
            <button class="btn btn-primary" onclick="window.location.href='certificados.php'">Escanear Certificado con código de barras</button>
        </div>

        <!-- ------------DATOS PERSONALES -->
        <div class="card shadow-sm p-4 mb-5">
            <h5 class="card-title mb-3">Datos Personales</h5>
            <?php
            $sql = $con->prepare("SELECT u.cedula, u.nombreCompleto nombre, u.Telefono, u.Correo,sas.nombreEmpresa,e.estado
                                                    FROM usuario u 
                                                    INNER JOIN estado e ON e.id_estado = u.id_estado 
                                                    INNER JOIN empresa sas ON sas.id_empresa = u.id_empresa 
                                                    WHERE cedula = ?");
            $sql->execute([$_SESSION['cedula']]);
            $sql = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach ($sql as $user) { ?>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Cédula:</strong> <?php echo $user['cedula'] ?></li>
                    <li class="list-group-item"><strong>Nombre:</strong> <?php echo $user['nombre'] ?></li>
                    <li class="list-group-item"><strong>Teléfono:</strong> <?php echo $user['Telefono'] ?></li>
                    <li class="list-group-item"><strong>Correo:</strong> <?php echo $user['Correo'] ?></li>
                    <li class="list-group-item"><strong>Empresa:</strong> <?php echo $user['nombreEmpresa'] ?></li>
                </ul>
                <div class="text-end">
                    <a href="editar.php" class="btn btn-warning">Editar Datos</a>
                </div>
            <?php } ?>
        </div>
    </div>

    
</body>


</html>