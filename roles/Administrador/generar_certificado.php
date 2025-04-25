<?php
session_start();
require_once('../../Database/database.php');
require_once('../../includes/validar_sesion.php');
$conexion = new database;
$con = $conexion->conectar();

require_once('../../libs/tcpdf/tcpdf.php');

function generarIDCertificado($length = 10)
{
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_certificado = generarIDCertificado();
    $nombrePersona = $_POST['nombrePersona'];
    $evento = $_POST['evento'];
    $fechaInicio = $_POST['fecha_inicio_evento'];
    $fechaFin = $_POST['fecha_fin_evento'];
    $propietario = $_SESSION['cedula'];

    $validacion = $con->prepare("SELECT * FROM licencias WHERE id_empresa = ? ORDER BY created_at DESC LIMIT 1 ");
    $validacion->execute([$_SESSION['id_empresa']]);

    $validacion1 = $validacion->fetch(PDO::FETCH_OBJ);

    if($validacion1 && $validacion1->id_estado == 1){

        if($validacion1->UsosGastados >= 1){

            $restar = $con->prepare("UPDATE licencias SET UsosGastados = UsosGastados-1 WHERE id_empresa = ? and id_licencia = ?");
            $restar->execute([$validacion1->id_empresa, $validacion1->id_licencia]);
            

        }else{

            $inactivar = $con->prepare("UPDATE licencias SET id_estado = 2 WHERE id_empresa = ? and id_licencia = ?");
            $inactivar->execute([$validacion1->id_empresa, $validacion1->id_licencia]);


            echo "<script>alert('Cantidad de usos agotados'); window.location.href = 'certificados.php'</script>";
            exit();
        }

    }else{
        echo "<script>alert('No se puede crear sin tener licencia, contactate con un asesor');  window.location.href = 'certificados.php'</script>";
        exit();
        
    }

    // Crear PDF horizontal sin márgenes
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

    // Fondo completo
    $pdf->Image('assets/img/decoracion_fondo.jpg', 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);

    // Encabezado
    $pdf->SetFont('times', 'B', 28);
    $pdf->SetTextColor(40, 40, 40);
    $pdf->Ln(35);
    $pdf->Cell(0, 20, 'Certificado de Graduación', 0, 1, 'C');

    // Cuerpo
    $pdf->SetFont('times', '', 18);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 10, "Se otorga el presente certificado a:", 0, 'C', 0, 1);

    $pdf->SetFont('times', 'B', 24);
    $pdf->MultiCell(0, 10, strtoupper($nombrePersona), 0, 'C', 0, 1);

    $pdf->SetFont('times', '', 18);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 10, "Por su destacada participación en el evento:", 0, 'C', 0, 1);

    $pdf->SetFont('times', 'I', 20);
    $pdf->MultiCell(0, 10, "\"$evento\"", 0, 'C', 0, 1);

    $pdf->SetFont('times', '', 16);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 10, "Celebrado del $fechaInicio al $fechaFin", 0, 'C', 0, 1);
    $pdf->MultiCell(0, 10, "Emitido por el Servicio Nacional de Certificados", 0, 'C', 0, 1);

    // Código de certificado con código de barras
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(0, 10, "Código del Certificado: $id_certificado", 0, 1, 'C');
    $pdf->write1DBarcode($id_certificado, 'C128', '', '', '', 30, 0.5, [
        'position' => 'C',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => [0, 0, 0],
        'bgcolor' => false,
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 10,
        'stretchtext' => 4
    ], 'N');

    // Directorio donde guardar el PDF
    $filePath = realpath('../../certificados') . "/certificado_graduacion_" . $id_certificado . ".pdf";

    // Salida final del PDF

    $ruta_archivo = "certificado_graduacion_" . $id_certificado . ".pdf";
    
    $pdf->Output($filePath, 'F');

    echo "<script>alert('Certificado creado correctamente'); window.location.href = 'certificados.php'</script>";

    //Registro en  base de datos 

    $stmt = $con->prepare("INSERT INTO certificados (id_certificado, nombrePersona, evento, fecha_inicio_evento, fecha_fin_evento, propietario, ruta_archivo) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_certificado, $nombrePersona, $evento, $fechaInicio, $fechaFin, $propietario, $ruta_archivo]);
} else {
    echo "<script>window.location.href = 'certificados.php'</script>";
    
    exit();
}
