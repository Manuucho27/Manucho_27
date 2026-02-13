<?php
// Funciones reutilizables para el registro de usuarios

/**
 * Validar datos del formulario de registro.
 * Devuelve un array con mensajes de error (vacío si no hay errores).
 * Campos esperados: nombre, username, email, password
 */
function validate_registration(array $data): array {
    $errors = [];

    // Nombre
    if (empty($data['nombre'])) {
        $errors[] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($data['nombre']) < 2) {
        $errors[] = 'El nombre debe tener al menos 2 caracteres.';
    }

    // Username
    if (empty($data['username'])) {
        $errors[] = 'El nombre de usuario es obligatorio.';
    } else {
        $uname = trim($data['username']);
        if (mb_strlen($uname) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/u', $uname)) {
            $errors[] = 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.';
        }
    }

    // Email
    if (empty($data['email'])) {
        $errors[] = 'El email es obligatorio.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'El email no tiene un formato válido.';
    }

    // Password
    if (empty($data['password'])) {
        $errors[] = 'La contraseña es obligatoria.';
    } elseif (mb_strlen($data['password']) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    return $errors;
}

/**
 * Generar token seguro para remember-me
 */
function generate_remember_token(int $bytes = 32): string {
    return bin2hex(random_bytes($bytes));
}

?>