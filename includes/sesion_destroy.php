<?php

session_start();

unset($_SESSION['cedula']);
unset($_SESSION['nombre']);
unset($_SESSION['id_empresa']);
unset($_SESSION['id_rol']);

session_destroy();
session_write_close();

header("location: ../index.php");
exit();