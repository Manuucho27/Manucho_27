<?php
session_start();
include '../php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener o crear carrito
$stmt = $conn->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $carrito_id = $result->fetch_assoc()['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO carritos (usuario_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $carrito_id = $conn->insert_id;
}

// Añadir al carrito si POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = min($_POST['cantidad'], 2); // Máximo 2

    // Verificar stock disponible y que el producto esté activo
    $stmt = $conn->prepare("SELECT stock, activo FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if (!$product || intval($product['activo']) === 0) {
        // Producto no disponible o inactivo
        header("Location: tienda.php");
        exit();
    }
    $stock = $product['stock'];
    $cantidad = min($cantidad, $stock); // No más que stock

    if ($cantidad > 0) {
        // Verificar si ya existe
        $stmt = $conn->prepare("SELECT id, cantidad FROM carrito_productos WHERE carrito_id = ? AND producto_id = ?");
        $stmt->bind_param("ii", $carrito_id, $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            $nueva_cantidad = min($existing['cantidad'] + $cantidad, 2); // Máximo 2 total
            $stmt = $conn->prepare("UPDATE carrito_productos SET cantidad = ? WHERE id = ?");
            $stmt->bind_param("ii", $nueva_cantidad, $existing['id']);
        } else {
            $stmt = $conn->prepare("INSERT INTO carrito_productos (carrito_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $carrito_id, $producto_id, $cantidad);
        }
        $stmt->execute();
    }
    header("Location: carrito.php");
    exit();
}

// Completar pedido
if (isset($_GET['completar'])) {
    // Calcular total
    $stmt = $conn->prepare("SELECT SUM(p.precio * cp.cantidad) as total FROM carrito_productos cp JOIN productos p ON cp.producto_id = p.id WHERE cp.carrito_id = ?");
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];

    // Crear pedido
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $pedido_id = $conn->insert_id;

    // Mover a pedido_productos
    $stmt = $conn->prepare("INSERT INTO pedido_productos (pedido_id, producto_id, cantidad, precio_unitario) SELECT ?, cp.producto_id, cp.cantidad, p.precio FROM carrito_productos cp JOIN productos p ON cp.producto_id = p.id WHERE cp.carrito_id = ?");
    $stmt->bind_param("ii", $pedido_id, $carrito_id);
    $stmt->execute();

    // Reducir stock
    $stmt = $conn->prepare("UPDATE productos p JOIN carrito_productos cp ON p.id = cp.producto_id SET p.stock = p.stock - cp.cantidad WHERE cp.carrito_id = ?");
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();

    // Marcar como inactivos los productos cuyo stock llegó a 0 o menos
    $stmt = $conn->prepare("UPDATE productos SET activo = 0 WHERE stock <= 0");
    $stmt->execute();

    // Eliminar de todos los carritos las referencias a productos inactivos
    $stmt = $conn->prepare("DELETE cp FROM carrito_productos cp JOIN productos p ON cp.producto_id = p.id WHERE p.activo = 0");
    $stmt->execute();

    // Vaciar carrito
    $stmt = $conn->prepare("DELETE FROM carrito_productos WHERE carrito_id = ?");
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();

    header("Location: tienda.php?pedido=exito");
    exit();
}

// Obtener productos en carrito
$query = "SELECT p.id, p.nombre, p.precio, cp.cantidad FROM carrito_productos cp JOIN productos p ON cp.producto_id = p.id WHERE cp.carrito_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $carrito_id);
$stmt->execute();
$productos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito - Manucho 27</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="../js/darkTheme.js"></script>
    <?php include '../plantillas/iconos.php'; ?>
</head>
<body>
    <?php include '../plantillas/nav.php'; ?>
    <div class="container mt-5">
        <h2>Carrito</h2>
        <?php if ($productos->num_rows > 0) { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; while ($row = $productos->fetch_assoc()) { $subtotal = $row['precio'] * $row['cantidad']; $total += $subtotal; ?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['precio']; ?>€</td>
                            <td><?php echo $row['cantidad']; ?></td>
                            <td><?php echo $subtotal; ?>€</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p>Total: <?php echo $total; ?>€</p>
            <a href="?completar=1" class="btn btn-success">Completar Pedido</a>
        <?php } else { ?>
            <p>Tu carrito está vacío.</p>
        <?php } ?>
    </div>
</body>
</html>