<?php
$host = 'localhost';
$db = 'tienda_web';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Asegurar sesión iniciada si no lo está
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Asegurar columna remember_token en usuarios (si no existe)
$check = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'remember_token'");
if ($check && $check->num_rows == 0) {
    $conn->query("ALTER TABLE usuarios ADD COLUMN remember_token VARCHAR(255) NULL");
}

// Restaurar sesión desde cookie 'userCookie' si existe y no hay sesión
if (!isset($_SESSION['user_id']) && !empty(
    $_COOKIE['userCookie']
)) {
    $token = $_COOKIE['userCookie'];
    $stmt = $conn->prepare("SELECT id, rol FROM usuarios WHERE remember_token = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $u = $res->fetch_assoc();
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['rol'] = $u['rol'];
        }
    }
}
?>