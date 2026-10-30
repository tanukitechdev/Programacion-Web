<?php

$host = 'localhost'; // Generalmente es localhost cuando la base de datos está en el mismo servidor
$db_nombre = 'proyecto_habitos'; // Reemplaza con el nombre de tu base de datos
$usuario_db = 'root'; // Reemplaza con tu usuario de MySQL
$password_db = ''; // Reemplaza con la contraseña de tu usuario de MySQL

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db_nombre;charset=utf8mb4", $usuario_db, $password_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "Error de conexión a la base de datos: " . $error->getMessage();
    exit();
}

?>