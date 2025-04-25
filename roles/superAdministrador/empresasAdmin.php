<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();


if (isset($_POST['registrarse'])) {


    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['Nombre'] ?? '';
    $telefono = $_POST['Telefono'] ?? '';
    $contrasena = $_POST['password'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $nit = $_POST['id_empresa'] ?? '';
    $id_estado = 2;
    $id_role = 2;


    if (!empty($cedula)) {
        $validacionRepetido = $con->prepare("SELECT cedula FROM usuario WHERE cedula = $cedula");
        $validacionRepetido->execute();
        $validacionRepetido = $validacionRepetido->fetchColumn();
    } else {
        echo "<script>alert('Debes colocar la cedula'); window.location.href = 'empresasAdmin.php'</script>";
        exit();
    }

    if ($validacionRepetido != 0 || $validacionRepetido != null) {

        echo "<script>alert('Cedula Duplicada, Usuario ya existente'); window.location.href = 'empresasAdmin.php'</script>";
        exit();
    }

    if (empty($cedula) || empty($nombre) || empty($telefono) || empty($contrasena) || empty($correo)) {
        echo "<script>alert('Datos Vacios, llena para continuar'); window.location.href = 'empresasAdmin.php'</script>";
        exit();
    }

    $sql = "INSERT INTO usuario (cedula, nombreCompleto, Telefono, Correo, contrasena, id_role, id_estado,id_empresa) VALUES (:cedula, :nombre, :telefono, :correo, :contrasena, :id_role, :id_estado, :nit)";
    $stmt = $con->prepare($sql);
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $hash);
    $stmt->bindParam(':id_role', $id_role);
    $stmt->bindParam(':id_estado', $id_estado);
    $stmt->bindParam(':nit', $nit);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario Registrado con exito');</script>";
    } else {
        echo "<script>alert('Error en el registro intenta de nuevo');</script>";
    }
}

//seccion de BORRAR USUARIOS
if (isset($_POST['borrar'])) {
    $borrar = $con->prepare("DELETE FROM usuario WHERE cedula = $_POST[cedula]");
    $borrar->execute();
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Admins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body class="bg-light" onload="form.cedula.focus()">

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
        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-4">Volver</button>
        <h2 class="mb-4">Registro de Usuario Administrador</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded shadow" name="form" id="form">

            <div class="col-md-6">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="number" name="cedula" id="cedula" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="Nombre" class="form-label">Nombre Completo</label>
                <input type="text" name="Nombre" id="Nombre" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="Telefono" class="form-label">Teléfono</label>
                <input type="number" name="Telefono" id="Telefono" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="correo" id="email" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="id_empresa" class="form-label">Empresa</label>
                <select name="id_empresa" id="id_empresa" class="form-select" required>
                    <option selected disabled value="">SELECCIONE UNA EMPRESA</option>
                    <?php
                    $empresas = $con->prepare("SELECT * FROM empresa");
                    $empresas->execute();
                    $selectEmpresas = $empresas->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($selectEmpresas as $empresa) { ?>
                        <option value="<?php echo $empresa['id_empresa']; ?>"><?php echo $empresa['nombreEmpresa']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12 text-end">
                <input type="submit" value="Registrarse" name="registrarse" class="btn btn-primary">
            </div>
        </form>

        <hr class="my-5">

        <h3 class="mb-3">Administradores Registrados</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Empresa</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuarios = $con->prepare("SELECT u.cedula, u.nombreCompleto nombre, u.Telefono, u.Correo,sas.nombreEmpresa,r.rol,e.estado
                                                FROM usuario u 
                                                INNER JOIN roles r ON r.id_rol = u.id_role 
                                                INNER JOIN estado e ON e.id_estado = u.id_estado 
                                                INNER JOIN empresa sas ON sas.id_empresa = u.id_empresa 
                                                WHERE id_role = 2;");
                    $usuarios->execute();
                    $usuarios = $usuarios->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($usuarios as $usuario) { ?>
                        <tr>
                            <td><?php echo $usuario['cedula'] ?></td>
                            <td><?php echo $usuario['nombre'] ?></td>
                            <td><?php echo $usuario['Telefono'] ?></td>
                            <td><?php echo $usuario['Correo'] ?></td>
                            <td><?php echo $usuario['nombreEmpresa'] ?></td>
                            <td><?php echo $usuario['rol'] ?></td>
                            <td><?php echo $usuario['estado'] ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="editar_usuario.php?cedula=<?php echo $usuario['cedula']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="" method="POST">
                                        <input type="number" name="cedula" value="<?php echo $usuario['cedula'] ?>" hidden>
                                        <button type="submit" onclick="return confirm('¿Deseas Eliminar a <?php echo $usuario['nombre'] ?>? ')" class="btn btn-sm btn-danger" name="borrar">Eliminar</button>
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