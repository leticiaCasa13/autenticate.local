<?php
session_start(); // Inicia la sesión para poder acceder a los datos de la sesión actual
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión actual
setcookie("token", "", time() - 3600, "/");
// Redirige al usuario a la página de inicio de sesión
header("Location: /");
exit();
