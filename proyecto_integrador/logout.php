<?php

error_log("Se ha accedido a logout.php");
session_start();
// Destruir todas las variables de sesión
session_unset();
// Destruir la sesión
session_destroy();
// Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit();

?>