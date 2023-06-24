<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loginb";

session_start();

// Generar un token CSRF si no está definido
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$resultMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: login.php");
        echo "Error de seguridad: token CSRF inválido.";
        exit();
    }

    $sql = "SELECT * FROM usuario WHERE username = '$username'";
    $result = $conn->query($sql);


    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
        if (password_verify($password, $hashedPassword)) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        } else {
            // Credenciales incorrectas
            $resultMessage = "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        // Credenciales incorrectas
        $resultMessage = "Nombre de usuario o contraseña incorrectos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amogus</title>
    <link rel="stylesheet" href="./form.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body class="text-center">
    <form class="form-signin" action="login.php" method="POST"> 
        <?php echo "<p>$resultMessage</p>";?>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <img class="mb-4" src="./imgl.png" alt="" width="100" height="60">
        <h1 class="h3 mb-3 fw-normal">Ingrese Sus Datos</h1>
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
        <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
        <a href="register.php">Registrate</a>
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
