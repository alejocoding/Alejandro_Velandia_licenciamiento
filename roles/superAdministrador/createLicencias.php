<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

if (isset($_POST['CrearLicencia'])) {

    $licencia = strtoupper(bin2hex(random_bytes(10)));
    $tipolicencia = $_POST['tipo_licencia'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $empresa = $_POST['id_empresa'] ?? '';
    $estado = 1;

    if (empty($licencia) || empty($valor) || empty($fecha_inicio) || empty($fecha_fin) || empty($empresa)) {
        echo "<script>alert('Datos vacios No se permite crear la licencia'); window.location.href = 'createLicencias.php'</script>";
        exit();
        return;
    }



    $sesionesActivas = $con->prepare("SELECT * FROM licencias WHERE id_empresa = ? ORDER BY created_at DESC LIMIT 1");
    $sesionesActivas->execute([$empresa]);

    $sesion = $sesionesActivas->fetch(PDO::FETCH_OBJ);



    if ($sesion) {

        if ($sesion->id_estado == 1) {

            echo "<script>alert('Ya se encuentra una licencia activa para esta empresa, no se puede crear otra'); window.location.href = 'createLicencias.php'</script>";
            exit();
        }
    }


    $consultaUsos = $con->prepare("SELECT usos FROM tipolicencia WHERE id_tipo_licencia = $tipolicencia");
    $consultaUsos->execute();

    $consultaUsos = $consultaUsos->fetchColumn();

    if (empty($consultaUsos) || $consultaUsos == 0) {
        echo "<script>alert('Error de creacion de licencia, revisar Cantidad de usos'); window.location.href = 'createLicencias.php'</script>";
        exit();
    }

    $insertar = $con->prepare("INSERT INTO licencias (id_licencia,id_tipo_licencia,valor,fecha_inicio,fecha_fn, UsosGastados, id_empresa,id_estado) VALUES (?,?,?,?,?,?,?,?)");

    if ($insertar->execute([$licencia, $tipolicencia, $valor, $fecha_inicio, $fecha_fin, $consultaUsos, $empresa, $estado])) {
        echo "<script>alert('licencia creada correctamente') </script>";
    }

    $cambiarEstado = $con->prepare("UPDATE usuario set id_estado =1  WHERE id_empresa = ? and id_estado = 2 and id_role = 2 or id_role= 3");
    $cambiarEstado->execute([$empresa]);
}


if (isset($_POST['borrar'])) {

    $borrarLicencia = $_POST['id_licencia'] ?? '';
    $borrarEmpresa = $_POST['id_empresa'] ?? '';


    if (empty($borrarLicencia) || empty($borrarEmpresa)) {
        echo "<script>alert('Error, no se puede eliminar la licencia'); window.location.href = 'createLicencias.php'</script>";
        exit();
    }

    $borrar = $con->prepare("DELETE FROM licencias WHERE id_licencia = ? and id_empresa = ?");
    if ($borrar->execute([$borrarLicencia, $borrarEmpresa])) {



        echo "<script>alert('Licencia Eliminada con exito'); window.location.href = 'createLicencias.php'</script>";
    } else {
        echo "<script>alert('Error, no se puede eliminar la licencia'); window.location.href = 'createLicencias.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Licencias</title>
    <!-- Bootstrap CSS -->
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
        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-3">Volver</button>

        <div class="mb-3">
            <span>¿No creaste la empresa?</span>
            <button onclick="window.location.href='create.php'" class="btn btn-primary">Crear Empresa</button>
        </div>
        <div class="mb-3">
            <span>¿No creaste el tipo?</span>
            <button onclick="window.location.href='tiposLicencias.php'" class="btn btn-info">Crear tipo Licencia</button>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Formulario de Licencias</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">



                    <div class="mb-3">
                        <label for="tipo_licencia" class="form-label">Tipo de Licencia</label>
                        <select name="tipo_licencia" id="tipo_licencia" class="form-select" required>
                            <option selected disabled value="">SELECCIONE UN TIPO DE LICENCIA</option>
                            <?php
                            $consultaTipoLicencia = $con->prepare("SELECT * FROM tipolicencia");
                            $consultaTipoLicencia->execute();
                            $selectTipoLicencia = $consultaTipoLicencia->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($selectTipoLicencia as $tipo_licencia) { ?>
                                <option value="<?php echo $tipo_licencia['id_tipo_licencia']; ?>"><?php echo $tipo_licencia['Nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor de la licencia</label>
                        <input type="number" name="valor" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Final</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_empresa" class="form-label">Empresa</label>
                        <select name="id_empresa" id="id_empresa" class="form-select">
                            <option selected disabled>SELECCIONE UNA EMPRESA</option>
                            <?php
                            $empresas = $con->prepare("SELECT * FROM empresa");
                            $empresas->execute();
                            $selectEmpresas = $empresas->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($selectEmpresas as $empresa) { ?>
                                <option value="<?php echo $empresa['id_empresa']; ?>"><?php echo $empresa['nombreEmpresa']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success" name="CrearLicencia">Crear Licencia</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Licencias Activas -->
    <div class="container mt-5">
        <h4 class="mb-3">Licencias Activas</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>identificador</th>
                        <th>Tipo de licencia</th>
                        <th>Empresa nit</th>
                        <th>Empresa</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Usos restantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $LicenciasActivas = $con->prepare(
                        "SELECT  licencias.id_licencia, tp.Nombre,sas.nombreEmpresa,sas.id_empresa, licencias.fecha_inicio, licencias.fecha_fn, licencias.UsosGastados Usos, e.estado FROM licencias 
                    INNER JOIN empresa sas ON licencias.id_empresa = sas.id_empresa 
                    INNER JOIN tipolicencia tp ON tp.id_tipo_licencia = licencias.id_tipo_licencia
                    INNER JOIN estado e ON e.id_estado = licencias.id_estado WHERE licencias.id_estado = 1;"
                    );
                    $LicenciasActivas->execute();
                    $LicenciasActivas = $LicenciasActivas->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($LicenciasActivas as $licenciaActiva) { ?>
                        <tr>
                            <td><?php echo $licenciaActiva['id_licencia']; ?></td>
                            <td><?php echo $licenciaActiva['Nombre']; ?></td>
                            <td><?php echo $licenciaActiva['id_empresa']; ?></td>
                            <td><?php echo $licenciaActiva['nombreEmpresa']; ?></td>
                            <td><?php echo $licenciaActiva['fecha_inicio']; ?></td>
                            <td><?php echo $licenciaActiva['fecha_fn']; ?></td>
                            <td><?php echo $licenciaActiva['Usos']; ?></td>
                            <td><?php echo $licenciaActiva['estado']; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                <a href="editarLicencias.php?editar=<?php echo $licenciaActiva['id_licencia']; ?>" class="btn btn-sm btn-success">Editar</a>
                                    <form action="" method="POST">
                                        <input type="text" name="id_empresa" value="<?php echo $licenciaActiva['id_empresa']; ?>" hidden>
                                        <input type="text" name="id_licencia" value="<?php echo $licenciaActiva['id_licencia']; ?>" hidden>
                                        <button type="submit" name="borrar" class="btn btn-danger btn-sm" onclick="return confirm('Deseas Borrar esta licencia')">Borrar Licencia</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Licencias Inactivas -->
    <div class="container mt-5">
        <h4 class="mb-3">Licencias Inactivas</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>Tipo de licencia</th>
                        <th>Empresa nit</th>
                        <th>Empresa</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Usos restantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Licenciasinactivas = $con->prepare(
                        "SELECT tp.Nombre,sas.nombreEmpresa,sas.id_empresa, licencias.fecha_inicio, licencias.fecha_fn, licencias.UsosGastados Usos, e.estado FROM licencias 
                    INNER JOIN empresa sas ON licencias.id_empresa = sas.id_empresa 
                    INNER JOIN tipolicencia tp ON tp.id_tipo_licencia = licencias.id_tipo_licencia
                    INNER JOIN estado e ON e.id_estado = licencias.id_estado WHERE licencias.id_estado = 2;"
                    );
                    $Licenciasinactivas->execute();
                    $Licenciasinactivas = $Licenciasinactivas->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($Licenciasinactivas as $licenciainactiva) { ?>
                        <tr>
                            <td><?php echo $licenciainactiva['Nombre']; ?></td>
                            <td><?php echo $licenciainactiva['id_empresa']; ?></td>
                            <td><?php echo $licenciainactiva['nombreEmpresa']; ?></td>
                            <td><?php echo $licenciainactiva['fecha_inicio']; ?></td>
                            <td><?php echo $licenciainactiva['fecha_fn']; ?></td>
                            <td><?php echo $licenciainactiva['Usos']; ?></td>
                            <td><?php echo $licenciainactiva['estado']; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="">
                                        <button type="submit" class="btn btn-danger btn-sm">Borrar Licencia</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            const valor = document.querySelector('input[name="valor"]').value.trim();
            const fechaInicio = document.getElementById("fecha_inicio").value;
            const fechaFin = document.getElementById("fecha_fin").value;
            const tipoLicencia = document.getElementById("tipo_licencia").value;
            const empresa = document.getElementById("id_empresa").value;

            let errores = [];

            if (valor === "") {
                errores.push("El valor de la licencia no puede estar vacío.");
            }

            if (!fechaInicio || !fechaFin) {
                errores.push("Ambas fechas deben estar completas.");
            } else if (fechaInicio > fechaFin) {
                errores.push("La fecha de inicio no puede ser posterior a la fecha de fin.");
            }

            const fechaActual = new Date().toISOString().split('T')[0];
            if (fechaFin < fechaActual) {
                errores.push("La fecha de fin no puede ser menor a la fecha actual.");
            }

            if (tipoLicencia === "") {
                errores.push("Debe seleccionar un tipo de licencia.");
            }

            if (empresa === "") {
                errores.push("Debe seleccionar una empresa.");
            }

            if (errores.length > 0) {
                e.preventDefault(); // Evita el envío del formulario
                alert(errores.join("\n"));
            }
        });
    </script>
</body>

</html>