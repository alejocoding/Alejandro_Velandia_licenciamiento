<?php

session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['crearEmpresa'])) {
    $nit = $_POST['id_empresa'];
    $empresa = $_POST['nombre_empresa'];
    $direccion = $_POST['direccion'];
    $id_estado = 1; // Estado por defecto

    $sql = $con->prepare("INSERT INTO empresa (id_empresa, nombreEmpresa, direccion, id_estado) VALUES (?, ?, ?, ?)");
    $sql->execute([$nit, $empresa, $direccion, $id_estado]);

    if ($sql) {
        echo "<script>alert('Empresa creada exitosamente');</script>";
    } else {
        echo "<script>alert('Error al crear la empresa');</script>";
    }
}

if (isset($_POST['borrar'])) {
    $borrar = $con->prepare("DELETE FROM empresa WHERE id_empresa = $_POST[id_empresa]");
    $borrar->execute();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create empresas</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mt-5">
        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-4">Volver</button>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Crear Empresa</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="id_empresa" class="form-label">Nit de la empresa</label>
                        <input type="number" name="id_empresa" id="id_empresa" required autofocus class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="nombre_empresa" class="form-label">Nombre de la empresa</label>
                        <input type="text" name="nombre_empresa" id="nombre_empresa" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="id_estado" class="form-label">Estado</label>
                        <select name="id_estado" id="id_estado" class="form-select">
                            <option selected disabled>SELECCIONE UN ESTADO</option>
                            <?php
                            $consultaEstado = $con->prepare("SELECT * FROM estado");
                            $consultaEstado->execute();
                            $selectEstado = $consultaEstado->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($selectEstado as $estado) {
                            ?>
                                <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado['estado']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <button type="submit" name="crearEmpresa" class="btn btn-success">Enviar</button>
                </form>
            </div>
        </div>
    </div>



    <!----- Empresas Activas -------------->

    <!-- Empresas Activas -->
    <div class="container mt-5">
        <h4 class="mb-3">Empresas Registradas</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-info">
                    <tr>
                        <th>NIT</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta1 = $con->prepare("SELECT c.id_empresa nit, c.nombreEmpresa empresa, c.direccion, e.estado FROM empresa c INNER JOIN estado e ON e.id_estado = c.id_estado");
                    $consulta1->execute();
                    $consulta1 = $consulta1->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($consulta1 as $empresa) { ?>
                        <tr>
                            <td><?php echo $empresa['nit']; ?></td>
                            <td><?php echo $empresa['empresa']; ?></td>
                            <td><?php echo $empresa['direccion']; ?></td>
                            <td><?php echo $empresa['estado']; ?></td>
                            <td>
                                <button class="btn btn-success btn-sm">EDITAR</button>
                                <form action="" method="POST">

                                    <input type="number" name="id_empresa" value="<?php echo $empresa['nit'] ?>" hidden>
                                    <button type="submit" onclick="return confirm('¿Deseas Eliminar la empresa <?php echo $empresa['empresa']; ?> ? ')" class="btn btn-sm btn-danger" name="borrar">Borrar</button>
                                </form>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Bootstrap JS Bundle (opcional si no usas JS de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>