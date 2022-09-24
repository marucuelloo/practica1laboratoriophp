<?php

$host="localhost";
$bd="practica1";
$usuario="root";
$contrasenia="";

try {
   $conexion=new PDO("mysql:host=$host; dbname=$bd", $usuario, $contrasenia);
   //$conexion=new PDO("mysql:host= sql101.epizy.com; dbname= epiz_32009825_libros", 	epiz_32009825, $PRdULGI2AHQk);
 
} catch (exeption $ex) {
    echo $ex->getMessage();
}
?>