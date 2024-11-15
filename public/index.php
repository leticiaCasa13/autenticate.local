<?php

require_once "../vendor/autoload.php";


$path = explode('/', trim( $_SERVER['REQUEST_URI']));
$views = '/views/';




// print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path[1] === 'register') {
    // Llama al método userSignUp con los datos del formulario
    SessionController::userSignUp($_POST['username'], $_POST['email'], $_POST['password']);
    redirect("/");
}





switch ($path[1]) {
    case '':
    case '/':
        if (SessionController::isLoggedIn()) {
            redirect("/admin");
        } else {
            require __DIR__ . $views . 'login.php';
            
        }
        
        break;

    case 'admin':

        if (SessionController::isLoggedIn()) {
            require __DIR__ . $views . 'admin.php';
        } else {
            redirect("/");
        }

        break;

    case 'not-found':
    default:
        http_response_code(404);
        require __DIR__ . $views . '404.php';
}


function redirect($url)
{
   header('Location: ' . $url);
   die();
}
