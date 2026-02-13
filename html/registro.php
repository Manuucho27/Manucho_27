<?php
session_start();
include '../php/config.php';
include '../php/register.php';

// En vez de mezclar validación y lógica directamente en la página,
// reutilizamos `validate_registration` y `generate_remember_token`.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? ''
    ];

    $errors = validate_registration($data);

    if (empty($errors)) {
        $nombre = $data['nombre'];
        $username = trim($data['username']);
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insertar con username además de nombre y email
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $username, $email, $password);
        if ($stmt->execute()) {
            // Auto-login: obtener id y crear token de recuerdo
            $user_id = $conn->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['rol'] = 'usuario';
            // Sólo crear remember token/cookie si el usuario marca 'remember'
            if (!empty($_POST['remember'])) {
                $token = generate_remember_token();
                $stmt2 = $conn->prepare("UPDATE usuarios SET remember_token = ? WHERE id = ?");
                if ($stmt2) {
                    $stmt2->bind_param("si", $token, $user_id);
                    $stmt2->execute();
                    setcookie('remember', $token, time() + (30*24*60*60), '/', '', false, true);
                }
            }
            header("Location: tienda.php");
            exit();
        } else {
            $error = "Error al registrar.";
        }
    } else {
        // Mostrar primero error simple al usuario
        $error = implode('<br>', $errors);
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
    <div class="container mt-5 auth-container">
        <div class="card auth-card">
            <div class="card-body">
                <h2>Registro</h2>
                <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert' aria-live='assertive'>$error</div>"; ?>
                <form method="POST" aria-label="Formulario de registro">
                    <div class="mb-3">
                        <label for="nombre">Nombre</label>
                        <input id="nombre" type="text" name="nombre" class="form-control" required>
                    </div>
                        <div class="mb-3">
                            <label for="username">Nombre de usuario</label>
                            <input id="username" type="text" name="username" class="form-control" required>
                        </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
                <p class="mt-3"><a class="muted-link" href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
            </div>
        </div>
    </div>
    <?php include '../plantillas/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>