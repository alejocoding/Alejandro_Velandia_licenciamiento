<?php
require_once('Database/database.php');

$conexion = new database;
$con = $conexion->conectar();   



if(isset($_POST['registrarse'])){

    

    $cedula = $_POST['cedula'];
    $nombre = $_POST['Nombre'];
    $telefono = $_POST['Telefono'];
    $contrasena = $_POST['password'];
    $correo = $_POST['correo'];
    $id_estado = 2; // Estado por defecto
    $id_role = 1; // Rol por defecto (2 para usuario normal)

    
    $sql = "INSERT INTO usuario (cedula, nombreCompleto, Telefono, Correo, contrasena, id_role, id_estado) VALUES (:cedula, :nombre, :telefono,:correo, :contrasena , :id_role, :id_estado)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', password_hash($contrasena, PASSWORD_DEFAULT));
    $stmt->bindParam(':id_role', $id_role);
    $stmt->bindParam(':id_estado', $id_estado);
    
    if($stmt->execute()){
        echo "Usuario registrado exitosamente.";
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>

<div class="container ">
    <form action="" method="POST" enctype="multipart/form-data" class="form">

        <div class="mb-3">
            <label for="cedula"> Cedula</label>
            <br>
            <input type="number" name="cedula" id="cedula" required class="">  
            
        </div>
        <div class="mb-3>
            <label for="Nombre">Nombre Completo</label>
            <br>
            <input type="text" name="Nombre" id="Nombre" required class="">
        </div>

        <div class="mb-3">
            <label for="Telefono">Telefono</label>
            <br>
            <input type="number" name="Telefono" id="Telefono" required class="">
        </div>
        <div class="mb-3">
            <label for="password"> Contrasena</label>
            <br>
            <input type="password" name="password" id="password" required class="">
        </div>
        <div class="mb-3">
            <label for="email">email</label>
            <br>
            <input type="email" name="correo" id="email" required class="">
        </div>

    

    <input type="submit" value="Registrarse" name="registrarse">
    </form>
    
</div>
</body>
</html>