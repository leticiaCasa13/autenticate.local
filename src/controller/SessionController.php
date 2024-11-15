<?php

class SessionController {

    private $connection;

    public function __construct() {
        $this->connection = DatabaseController::connect();
    }

    // Método para registrar usuario
    public static function userSignUp($username, $email, $password) {
        if ((new self)->exist($username, $email)) {
            echo "Username or email already exists";
            return;
        } else {
            try {
                $sql = "INSERT INTO usuarioAutent (username, email, password) VALUES (:username, :email, :password)";

                // Hasheamos la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $statement = (new self)->connection->prepare($sql);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':email', $email);
                $statement->bindValue(':password', $hashed_password);
                
                $statement->execute();

                echo "Usuario registrado exitosamente";
                return;
            } catch (PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
                return null;
            }
        }
    }

    // Método para iniciar sesión
    public static function userLogin($username, $password) {
        if (!(new self)->exist($username)) {
            return false;
        } else {
            try {
                $sql = "SELECT id, password FROM usuarioAutent WHERE username = :username";

                $statement = (new self)->connection->prepare($sql);
                $statement->bindValue(':username', $username);
                $statement->setFetchMode(PDO::FETCH_OBJ);
                $statement->execute();

                $user = $statement->fetch();

                if ($user && password_verify($password, $user->password)) {
                   if (session_status() === PHP_SESSION_NONE) { 
                       session_start();
                   }

                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $username;

                    return self::generateToken($user);
                } else {
                    return false;
                }
            } catch (PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
                return false;
            }
        }
    }

    // Generar un token de sesión y guardarlo en la base de datos
    private static function generateToken($user) {
        if (isset($_SESSION['user_id'])) {
            $token = bin2hex(random_bytes(16));
            setcookie("token", $token, time() + (86400 * 30), "/"); // 30 días

            $statement = (new self)->connection->prepare("UPDATE usuarioAutent SET token = :token WHERE id = :id");
            $statement->bindValue(':token', $token);
            $statement->bindValue(':id', $user->id);
            $statement->execute();

            return true;
        } else {
            return false;
        }
    }

    // Verificar si el usuario ya existe por nombre de usuario o correo electrónico
    public static function exist($username, $email = null) {
        if ($email === null) {
            try {
                $sql = "SELECT * FROM usuarioAutent WHERE username = :username";
                $statement = (new self)->connection->prepare($sql);
                $statement->bindValue(':username', $username);
                $statement->setFetchMode(PDO::FETCH_OBJ);
                $statement->execute();

                $result = $statement->fetch();
                return $result ? true : false;
            } catch (PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        } else {
            try {
                $sql = "SELECT * FROM usuarioAutent WHERE username = :username OR email = :email";
                $statement = (new self)->connection->prepare($sql);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':email', $email);
                $statement->setFetchMode(PDO::FETCH_OBJ);
                $statement->execute();

                $result = $statement->fetch();
                return $result ? true : false;
            } catch (PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        }
    }

    // Verificar el token de sesión en la cookie
    public static function verifyTokenCookie() {
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
 
       }
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];

            $statement = (new self)->connection->prepare("SELECT id, username FROM usuarioAutent WHERE token = :token");
            $statement->bindValue(":token", $token);
            $statement->setFetchMode(PDO::FETCH_OBJ);
            $statement->execute();
            $user = $statement->fetch();

            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;

                return true;
            } else {
                setcookie("token", "", time() - 3600, "/"); // Eliminar cookie
                echo "Token inválido!";
                return false;
            }
        } else {
            return false;
        }
    }

    // Verificar si el usuario está autenticado
    public static function isLoggedIn() {
        return self::verifyTokenCookie();
    }
}

