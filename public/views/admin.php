<?php
session_start();
if (!isset($_SESSION['user_id'])) {

//Redirige al usuario a la página de inicio de sesión si no está  autenticado
header("Location: /public/views/login.php");
exit();

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>

<h1>Panel Admin</h1>

<?php
// Mostrar el nombre de usuario almacenado en la sesión
echo "<p>Usuario: " . htmlspecialchars($_SESSION['username']) . "</p>";
?>

<p>Área administración.</p>

<!-- Opcional: Agrega más secciones o enlaces -->
<ul>
    <li><a href="perfil.php">Ver Perfil</a></li>
    <li><a href="configuracion.php">Configuración</a></li>
    <li><a href="/public/views/logout.php">Cerrar Sesión</a></li>
</ul>

</body>
</html>
