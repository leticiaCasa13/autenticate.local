<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = htmlspecialchars($_POST["username"]);
  $password = htmlspecialchars($_POST["password"]);

  if (SessionController::userLogin($username, $password)) {
    header("Location: /admin");
    exit();
  } else {
    $error = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
  }
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión</title>
    <link href="/assets/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center bg-light" style="height: 100vh;">

<div class="form-signin text-center">
  <h1 class="h3 mb-3 fw-normal">Sign In</h1>

  <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="form-floating mb-2">
      <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" required>
      <label for="floatingUsername">Username</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <div class="form-check text-start my-3">
      <input class="form-check-input" type="checkbox" value="remember-me" id="rememberMe">
      <label class="form-check-label" for="rememberMe">Remember me</label>
    </div>

    <button class="btn btn-primary w-100" type="submit">Sign in</button>
  </form>
</div>

<script src="/assets/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 

