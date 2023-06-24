<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loginb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar la variable para almacenar el mensaje de resultado
$resultMessage = "";

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario de registro
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el nombre de usuario ya está registrado
    $sql = "SELECT * FROM usuario WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El nombre de usuario ya existe
        $resultMessage = "El nombre de usuario '$username' ya está registrado.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (username, password) VALUES ('$username', '$hashedPassword')";
        if ($conn->query($sql) === TRUE) {
            // Registro exitoso, redireccionar al formulario de inicio de sesión
            header("Location: login.php");
            exit();
        } else {
            $resultMessage = "Error al registrar el usuario: " . $conn->error;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="./form.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body class="text-center">
    <form class="form-signin" action="register.php" method="POST">
        <?php
        // Mostrar el mensaje de resultado en el lugar correspondiente
        echo "<p>$resultMessage</p>";
        ?> 
        <img class="mb-4" src="./imgl.png" alt="" width="100" height="60">
        <h1 class="h3 mb-3 fw-normal">Registra Tus Datos</h1>
        <div class="form-floating">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        <label for="username">Usuario</label>
        </div>
        <div class="form-floating mt-2">
        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
        <label for="password">Contraseña</label>
        </div>
        <div class="checkbox mb-3">
        <label>
        <input type="checkbox" value="remember-me"> Recuérdame
        </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Registrarse</button>
        <a href="Login.php">Login</a>
    </form> 
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
