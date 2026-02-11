<?php
session_start();
// Borrar token de remember en DB si existía
include '../php/config.php';
if (isset($_SESSION['user_id'])) {
	$stmt = $conn->prepare("UPDATE usuarios SET remember_token = NULL WHERE id = ?");
	if ($stmt) {
		$stmt->bind_param("i", $_SESSION['user_id']);
		$stmt->execute();
	}
}
// Borrar cookie y destruir sesión
setcookie('remember', '', time() - 3600, '/', '', false, true);
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>