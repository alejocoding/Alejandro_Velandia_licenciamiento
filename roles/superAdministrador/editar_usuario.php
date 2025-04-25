<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

// Si se está editando un usuario
if (isset($_GET['cedula'])) {
    $cedula_editar = $_GET['cedula'];

    // Obtenemos los datos del usuario para editar
    $editar_usuario = $con->prepare("SELECT * FROM usuario WHERE cedula = :cedula");
    $editar_usuario->bindParam(':cedula', $cedula_editar);
    $editar_usuario->execute();
    $usuario_a_editar = $editar_usuario->fetch(PDO::FETCH_ASSOC);
}


// Si se actualizan los datos
if (isset($_POST['actualizar'])) {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['Nombre'];
    $telefono = $_POST['Telefono'];
    $correo = $_POST['correo'];
    $id_estado = $_POST['id_estado'];




    // Si la contraseña se ha modificado

    // Si la contraseña no se ha cambiado, solo actualizar los demás campos
    $sql = "UPDATE usuario SET nombreCompleto = :nombre, Telefono = :telefono, Correo = :correo ,id_estado = :estado WHERE cedula = :cedula";


    $stmt = $con->prepare($sql);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':estado', $id_estado);

    if (isset($contrasena)) {
        $stmt->bindParam(':contrasena', $contrasena);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Usuario actualizado con éxito'); window.location.href='empresasAdmin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el usuario');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body class="bg-light">

    <div class="container py-5">
        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-4">Volver</button>

        <?php if (isset($usuario_a_editar)) { ?>
            <h2 class="mb-4">Editar Usuario Administrador</h2>

            <form action="" method="POST" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded shadow" name="form" id="form">
                <input type="hidden" name="cedula" value="<?php echo $usuario_a_editar['cedula']; ?>">

                <div class="col-md-6">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="number" name="cedula" id="cedula" class="form-control" value="<?php echo $usuario_a_editar['cedula']; ?>" disabled>
                </div>

                <div class="col-md-6">
                    <label for="Nombre" class="form-label">Nombre Completo</label>
                    <input type="text" name="Nombre" id="Nombre" class="form-control" value="<?php echo $usuario_a_editar['nombreCompleto']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="Telefono" class="form-label">Teléfono</label>
                    <input type="number" name="Telefono" id="Telefono" class="form-control" value="<?php echo $usuario_a_editar['Telefono']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="id_estado" class="form-label">Estado</label>
                    <select name="id_estado" id="id_estado" class="form-select">


                        <?php
                        $consultaEstado = $con->prepare("SELECT * FROM estado");
                        $consultaEstado->execute();
                        $selectEstado = $consultaEstado->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($selectEstado as $estado) {
                        ?>
                            if($usuario_a_editar['id_estado'] == $estado['id_estado']){

                            <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado['estado']; ?></option>
                            }

                            <option value="<?php echo $estado['id_estado']; ?>"><?php echo $estado['estado']; ?></option>
                            
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="email" class="form-control" value="<?php echo $usuario_a_editar['Correo']; ?>" required>
                </div>



                <div class="col-12 text-end">
                    <input type="submit" value="Actualizar Usuario" name="actualizar" class="btn btn-primary">
                </div>
            </form>
        <?php } else { ?>
            <p>No se pudo cargar el usuario a editar.</p>
        <?php } ?>

    </div>

</body>

</html>