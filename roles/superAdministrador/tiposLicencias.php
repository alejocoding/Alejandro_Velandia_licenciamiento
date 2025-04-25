<?php
session_start();
require_once('../../Database/database.php');
$conexion = new database;
$con = $conexion->conectar();

// Verificar si el usuario tiene sesión activa
if (!isset($_SESSION['cedula'])) {
    header("Location: ../index.php");
    exit();
}

// Agregar un nuevo tipo de licencia
if (isset($_POST['crear'])) {
    $nombre = $_POST['nombre'] ?? '';
    $usos = $_POST['usos'] ?? 0;

    if (empty($nombre) || empty($usos)) {
        echo "<script>alert('Por favor, complete todos los campos.'); window.location.href = 'tiposlicencias.php'</script>";
        exit();
    }

    $insertar = $con->prepare("INSERT INTO tipolicencia (Nombre, Usos) VALUES (?, ?)");
    $insertar->execute([$nombre, $usos]);

    echo "<script>alert('Tipo de licencia creado exitosamente'); window.location.href = 'tiposlicencias.php'</script>";
}

// Obtener todos los tipos de licencia existentes
$tiposLicencia = $con->prepare("SELECT * FROM tipolicencia");
$tiposLicencia->execute();
$tiposLicencia = $tiposLicencia->fetchAll(PDO::FETCH_ASSOC);

// ELIMINAR

if (isset($_POST['eliminar'])) {
    $id_tipo_licencia = $_POST['id_tipo_licencia'] ?? '';

    if (!empty($id_tipo_licencia)) {
        // Eliminar tipo de licencia
        $eliminar = $con->prepare("DELETE FROM tipolicencia WHERE id_tipo_licencia = ?");
        $eliminar->execute([$id_tipo_licencia]);

        echo "<script>alert('Tipo de licencia eliminado exitosamente'); window.location.href = 'tiposlicencias.php'</script>";
    } else {
        echo "<script>alert('Error: Tipo de licencia no encontrado'); window.location.href = 'tiposlicencias.php'</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Licencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body class="bg-light" onload="focusLicencia.nombre.focus()">
    <header class="bg-white border-bottom shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <div class="card bg-success text-white px-3 py-2">
                <strong>Gestión de Licencias</strong>
            </div>
            <button class="btn btn-outline-danger" onclick="window.location.href='../../includes/sesion_destroy.php'">Cerrar sesión</button>
        </div>
    </header>

    <div class="container py-5">
        <button onclick="window.location.href='index.php'" class="btn btn-secondary mb-4">Volver</button>
        <div class="mb-4 text-center">
            <h1 class="mb-3">Crear Nuevo Tipo de Licencia</h1>
            <p class="lead">Desde aquí puedes crear nuevos tipos de licencias para tu sistema.</p>
        </div>

        <!-- Formulario para crear nuevo tipo de licencia -->
        <div class="mb-5">
            <form action="" method="POST" name="focusLicencia">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Tipo de Licencia</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="usos" class="form-label">Número de Usos</label>
                    <input type="number" class="form-control" id="usos" name="usos" required>
                </div>
                <button type="submit" class="btn btn-primary" name="crear">Crear Tipo de Licencia</button>
            </form>
        </div>

        <!-- Tabla para mostrar los tipos de licencia existentes -->
        <h3 class="mb-4">Tipos de Licencias Existentes</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($tiposLicencia as $tipo) { ?>
                        <tr>
                            <td><?php echo $tipo['id_tipo_licencia']; ?></td>
                            <td><?php echo $tipo['Nombre']; ?></td>
                            <td><?php echo $tipo['Usos']; ?></td>
                            <td>
                                <!-- Formulario para eliminar tipo de licencia -->
                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="id_tipo_licencia" value="<?php echo $tipo['id_tipo_licencia']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" name="eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este tipo de licencia?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>