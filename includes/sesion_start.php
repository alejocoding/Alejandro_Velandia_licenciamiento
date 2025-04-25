<?php
require_once('../Database/database.php');

$conexion = new database;
$con = $conexion->conectar();


if (isset($_POST['logearse'])) {



    $cedula = $_POST['cedula'];

    $sql = $con->prepare("SELECT * FROM usuario WHERE cedula = $cedula");
    $sql->execute();

    if ($sql = $sql->fetch(PDO::FETCH_ASSOC)); {

        $password = $_POST['password'];
        $hash = $sql['contrasena'];


        if (password_verify($password, $hash) || $password == $hash) {

            if ($sql['id_estado'] != 1 && $sql['id_role'] == 3) {
                echo "<script>alert('Usuario inactivo');</script>";
                echo "<script>window.location.href='../index.php';</script>";
                exit();
            }else if($sql['id_estado'] != 1 and $sql['id_role'] == 2){
                echo "<script>alert('Licencia Terminada, contacta con el admin para renovar');</script>";
                echo "<script>window.location.href='../index.php';</script>";
                exit();
            }

            switch ($sql['id_role']) {
                case 1:
                    session_start();
                    $_SESSION['cedula'] = $sql['cedula'];
                    $_SESSION['nombre'] = $sql['nombreCompleto'];
                    $_SESSION['id_rol'] = $sql['id_role'];

                    header("Location: ../roles/superAdministrador/index.php");
                    break;

                case 2:
                    session_start();
                    $_SESSION['cedula'] = $sql['cedula'];
                    $_SESSION['nombre'] = $sql['nombreCompleto'];
                    $_SESSION['id_empresa'] = $sql['id_empresa'];
                    $_SESSION['id_rol'] = $sql['id_role'];
                    header("Location: ../roles/Administrador/index.php");
                    break;
                
                case 3:
                    session_start();
                    $_SESSION['cedula'] = $sql['cedula'];
                    $_SESSION['nombre'] = $sql['nombreCompleto'];
                    $_SESSION['id_empresa'] = $sql['id_empresa'];
                    $_SESSION['id_rol'] = $sql['id_role'];
                    header("Location: ../roles/usuario/index.php");
                    break;


                default:
                    echo "<script>alert('Rol No encontrado');</script>";
            }
        } else {
            echo "<script>alert('Contraseña Incorrecta');</script>";
        }
    }
}

"hola mundo";
