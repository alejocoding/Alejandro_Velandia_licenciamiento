<?php
if(!$_SESSION['cedula']){
    unset($_SESSION['nombre']);
    $_SESSION = array();
    session_destroy();
    session_write_close();
    

    echo "<script>
            alert('ACCIÓN NO PERMITIDA'); 
            window.location.href = '../../index.php';
            
            </script>";
    exit(); 
}