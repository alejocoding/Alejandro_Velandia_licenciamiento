<?php
class database{

    private $host="localhost";
    private $usuario="root";
    private $contraseña ="";
    private $database="licenciamiento";
    private $charset="utf8";


    function conectar(){


        try {
            $conexion = "mysql:host=" .$this->host . "; dbname=" . $this->database . "; charset=" . $this->charset;
            $opcion = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false];
            
            $pdo = new PDO($conexion, $this->usuario, $this->contraseña, $opcion);

            return $pdo;

        } catch (PDOException $e) {

           echo "Error de conexion: " . $e->getMessage();
           exit();
        }

       
    

    }

    
} 