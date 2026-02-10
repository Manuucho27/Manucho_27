<?php
session_start();
include '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Permitimos iniciar sesión con email o con username.
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Buscamos por email o por username (ambos únicos en la tabla).
    $stmt = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE email = ? OR username = ? LIMIT 1");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];
            // Sólo generar token de recuerdo y cookie si marcó 'remember'
            if (!empty($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $stmt2 = $conn->prepare("UPDATE usuarios SET remember_token = ? WHERE id = ?");
                if ($stmt2) {
                    $stmt2->bind_param("si", $token, $user['id']);
                    $stmt2->execute();
                    setcookie('remember', $token, time() + (30*24*60*60), '/', '', false, true);
                }
            }
            header("Location: tienda.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Manucho 27</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="../js/darkTheme.js"></script>
    <?php include '../plantillas/iconos.php'; ?>
</head>
<body>
    <?php include '../plantillas/nav.php'; ?>
    <div class="container mt-5 auth-container">
        <div class="card auth-card">
            <div class="card-body">
                <h2>Login</h2>
                <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert' aria-live='assertive'>$error</div>"; ?>
                <form method="POST" aria-label="Formulario de inicio de sesión">
                    <div class="mb-3">
                        <label for="login">Usuario o Email</label>
                        <input id="login" type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>
                <p class="mt-3"><a class="muted-link" href="registro.php">¿No tienes cuenta? Regístrate</a></p>
            </div>
        </div>
    </div>
    <?php include '../plantillas/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>