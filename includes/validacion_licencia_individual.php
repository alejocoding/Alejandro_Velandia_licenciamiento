<?php

require_once('../../Database/database.php');

$conexion = new database;
$con = $conexion->conectar();

$cedula = $_SESSION['cedula'] ?? null;
$id_empresa = $_SESSION['id_empresa'] ?? null;
$nombre = $_SESSION['nombre'] ?? '';

if (!$cedula || !$id_empresa) {
    echo "Sesión inválida.";
    exit();
}

// Iniciar transacción
$con->beginTransaction();

try {
    // Obtener la licencia de la empresa del usuario actual
    $licenciaStmt = $con->prepare("SELECT * FROM licencias WHERE id_empresa = ? AND id_estado = 1");
    $licenciaStmt->execute([$id_empresa]);
    $licencia = $licenciaStmt->fetch(PDO::FETCH_ASSOC);

    $fechaActual = date("Y-m-d");

    // Si hay una licencia activa
    if ($licencia) {
        $vencida = $licencia['fecha_fn'] < $fechaActual;
        $sinUsos = $licencia['UsosGastados'] < 1;

        // Si está vencida o sin usos
        if ($vencida || $sinUsos) {
            // Inactivar licencia
            $inactivarLicencia = $con->prepare("UPDATE licencias SET id_estado = 2 WHERE id_licencia = ?");
            $inactivarLicencia->execute([$licencia['id_licencia']]);

            // Inactivar usuario actual
            $inactivarUsuario = $con->prepare("UPDATE usuario SET id_estado = 2 WHERE cedula = ?");
            $inactivarUsuario->execute([$cedula]);

           
            echo "<script>alert('Licencia vencida o sin usos. Tu cuenta ha sido inactivada.'); window.location.href='logout.php';</script>";
        }
    } else {
        $inactivarLicencia = $con->prepare("UPDATE licencias SET id_estado = 2 WHERE id_licencia = ?");
        $inactivarLicencia->execute([$licencia['id_licencia']]);

        $inactivarUsuario = $con->prepare("UPDATE usuario SET id_estado = 2 WHERE cedula = ?");
        $inactivarUsuario->execute([$cedula]);

        echo "<script>alert('Licencia vencida o sin usos. Tu cuenta ha sido inactivada.'); window.location.href='logout.php';</script>";
    }

    // Confirmar cambios
    $con->commit();
} catch (Exception $e) {
    // Revertir en caso de error
    $con->rollBack();
    echo "Error al procesar: " . $e->getMessage();
}
