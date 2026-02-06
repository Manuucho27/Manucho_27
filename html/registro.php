<?php
session_start();
include '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error al registrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Manucho 27</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="../js/darkTheme.js"></script>
    <?php include '../plantillas/iconos.php'; ?>
</head>
<body>
    <?php include '../plantillas/nav.php'; ?>
    <div class="container mt-5">
        <h2>Registro</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    </div>
</body>
</html>