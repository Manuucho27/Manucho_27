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


/*
 * Restaurar sesión desde cookie 'remember'
 * - Implementar un mecanismo "remember me" (login persistente)
 *   Cuando en el login se crea y guarda un token en la tabla `usuarios` (campo
 *   `remember_token`) y se envía el mismo token al navegador como cookie
 *   (`userCookie`), este bloque permite restaurar la sesión automáticamente
 *   si la cookie está presente y no existe sesión iniciada
 * - En resumen:
 *   1) El navegador envía la cookie `userCookie` con un token.
 *   2) Si no hay sesión activa, buscamos en la tabla `usuarios` un usuario
 *      cuyo `remember_token` coincida con ese token.
 *   3) Si se encuentra, se recrea `$_SESSION['user_id']` y `$_SESSION['rol']`
 *   - El token será largo y generado aleatoriamente (no predecible)
 *   - Si el usuario cierra sesión (logout), eliminamos el token tanto
 *     en la cookie como en la base de datos (borrar `remember_token` para ese usuario)
 */
if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
    $token = $_COOKIE['remember'];
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