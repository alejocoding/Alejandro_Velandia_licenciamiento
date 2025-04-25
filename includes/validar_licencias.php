    <?php

    require_once('../../Database/database.php');

    $conexion = new database;
    $con = $conexion->conectar();

    // Empezamos una transacción para asegurar que todos los cambios sean atómicos
    $con->beginTransaction();

    try {
        // Obtenemos todas las licencias
        $licencias = $con->prepare("SELECT * FROM licencias WHERE id_estado = 1");
        $licencias->execute();
        $licencia = $licencias->fetchAll();

        foreach ($licencia as $l) {
            if ($l['id_estado'] == 1) {
                // Verificamos si los UsosGastados son menores a 1
                if ($l['UsosGastados'] < 1) {

                    $updateLicencia = $con->prepare("UPDATE licencias SET id_estado = 2 WHERE id_licencia = ?");
                    $updateLicencia->execute([$l['id_licencia']]);

                    // Aquí terminamos el script y revertimos la transacción
                    
                }
            } else {
                // Inactivamos los usuarios si la licencia no está activa
                $inactivarUsuario = $con->prepare("UPDATE usuario SET id_estado = 2 WHERE id_empresa = ? and id_role = 2");
                $inactivarUsuario->execute([$l['id_empresa']]);
            }
        }

        // Obtenemos la fecha actual
        $hoy = date("Y-m-d");

        // Buscamos las licencias expiradas
        $licenciasExpiradas = $con->prepare("SELECT licencias.id_empresa, e.nombreEmpresa FROM licencias INNER JOIN empresa e ON e.id_empresa = licencias.id_empresa WHERE fecha_fn < ? AND licencias.id_estado = 1");
        $licenciasExpiradas->execute([$hoy]);
        $empresas = $licenciasExpiradas->fetchAll(PDO::FETCH_ASSOC);

        foreach ($empresas as $empresa) {
            $id_empresa = $empresa['id_empresa'];

            // Cambiamos el estado de la licencia a inactiva (id_estado = 2)
            $updateLicencia = $con->prepare("UPDATE licencias SET id_estado = 2 WHERE id_empresa = ? AND id_estado = 1");
            $updateLicencia->execute([$id_empresa]);

            // Inactivamos los usuarios administradores de esa empresa
            $inactivarUsuarios = $con->prepare("UPDATE users SET activo = 0 WHERE id_empresa = ? AND rol = 1");
            $inactivarUsuarios->execute([$id_empresa]);
        }

        // Si todo fue bien, confirmamos la transacción
        $con->commit();
        

    } catch (Exception $e) {
        // Si ocurre algún error, revertimos todos los cambios
        $con->rollBack();
    
    }
    ?>
