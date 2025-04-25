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
    $nit = $_SESSION['id_empresa'] ?? '';
    $id_role = 3;
    $id_estado = $_POST['id_estado'] ?? '';

   


    if (!empty($cedula)) {
        $validacionRepetido = $con->prepare("SELECT cedula FROM usuario WHERE cedula = $cedula");
        $validacionRepetido->execute();
        $validacionRepetido = $validacionRepetido->fetchColumn();
    } else {
        echo "<script>alert('Debes colocar la cedula'); window.location.href = 'CrearUsuarios.php'</script>";
        exit();
    }

    if ($validacionRepetido != 0 || $validacionRepetido != null) {

        echo "<script>alert('Cedula Duplicada, Usuario ya existente'); window.location.href = 'CrearUsuarios.php'</script>";
        exit();
    }

    if (empty($cedula) || empty($nombre) || empty($telefono) || empty($contrasena) || empty($correo) || empty($id_role) || empty($id_estado)) {
        echo "<script>alert('Datos Vacios, llena para continuar'); window.location.href = 'CrearUsuarios.php'</script>";
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

if(isset($_POST['borrar'])){

    if($_POST['cedula'] == $_SESSION['cedula']){
        echo "<script>alert('No se puede Eliminar el Usuario que tiene la sesion Activa'); window.location.href = 'CrearUsuarios.php'</script>";
        exit();
    }

    $borrar = $con->prepare("DELETE FROM usuario WHERE id_empresa = $_SESSION[id_empresa] and cedula = $_POST[cedula]");
    $borrar->execute();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body class="bg-light py-4">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Crear Usuarios</h2>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>

        <div class="alert alert-info">
            Bienvenido a la sección para crear los usuarios
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded shadow mb-5">
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
                <label for="id_estado" class="form-label">Estado</label>
                <select name="id_estado" id="id_estado" class="form-select" required>
                    <option selected disabled value="">SELECCIONE UN ESTADO</option>
                    <?php
                    $estados = $con->prepare("SELECT * FROM estado");
                    $estados->execute();
                    $selectEstados = $estados->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($selectEstados as $estado) { ?>
                        <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado['estado']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12 text-end">
                <input type="submit" value="Registrarse" name="registrarse" class="btn btn-primary">
            </div>
        </form>

        <h4 class="mb-3">Usuarios Registrados</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuarios = $con->prepare("SELECT u.cedula, u.nombreCompleto nombre, u.Telefono, u.Correo, r.rol, e.estado
                                               FROM usuario u
                                               INNER JOIN roles r ON r.id_rol = u.id_role
                                               INNER JOIN estado e ON e.id_estado = u.id_estado
                                               WHERE id_role != 1  and id_role != 2 AND id_empresa = ?");
                    $usuarios->execute([$_SESSION['id_empresa']]);
                    $usuarios = $usuarios->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($usuarios as $usuario) {
                        // Estilo condicional por rol
                        $rowClass = ($usuario['rol'] == 'Administrador') ? 'bg-danger' : 'bg-warning-subtle';
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td><?php echo $usuario['cedula']; ?></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['Telefono']; ?></td>
                            <td><?php echo $usuario['Correo']; ?></td>
                            <td><?php echo $usuario['rol']; ?></td>
                            <td><?php echo $usuario['estado']; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-warning">Editar</button>
                                    <form action="" method="POST">
                                        <input type="hidden" name="cedula" value="<?php echo $usuario['cedula']; ?>">
                                        <button type="submit" onclick="return confirm('¿Deseas Eliminar a <?php echo $usuario['nombre']; ?>?')" class="btn btn-sm btn-danger" name="borrar">Eliminar</button>
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

</html>