<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();


header('Content-Type: application/json');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $id = $con->prepare("SELECT ruta_archivo FROM certificados WHERE id_certificado = ?");
    
    $id->execute([$codigo]);
    $ruta = $id->fetchColumn();

    if (!empty($ruta)) {
        echo json_encode([
            "existe" => true,
            "ruta" => $ruta
        ]);
    } else {
        echo json_encode(["existe" => false]);
    }
} else {
    echo json_encode(["existe" => false]);
}
