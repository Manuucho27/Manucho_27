<?php
session_start();
include '../php/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    $imagen_tipo = $_FILES['imagen']['type'];

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen, imagen_tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $nombre, $descripcion, $precio, $stock, $imagen, $imagen_tipo);
    $stmt->execute();
}

if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $producto_editar = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

$result = $conn->query("SELECT id, nombre, descripcion, precio, stock FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manucho 27</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="../js/darkTheme.js"></script>
    <?php include '../plantillas/iconos.php'; ?>
</head>
<body>
    <?php include '../plantillas/nav.php'; ?>
    <div class="container mt-5">
        <h2>Panel de Admin</h2>
        <h3>Subir Producto</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre">Nombre</label>
                <input id="nombre" type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio">Precio</label>
                <input id="precio" type="number" step="0.01" name="precio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock">Stock</label>
                <input id="stock" type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="imagen">Imagen</label>
                <input id="imagen" type="file" name="imagen" class="form-control" required>
            </div>
            <button type="submit" name="subir" class="btn btn-primary">Subir</button>
        </form>

        <?php if (isset($producto_editar)) { ?>
        <h3>Editar Producto</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $producto_editar['id']; ?>">
            <div class="mb-3">
                <label for="nombre_ed">Nombre</label>
                <input id="nombre_ed" type="text" name="nombre" class="form-control" value="<?php echo $producto_editar['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion_ed">Descripción</label>
                <textarea id="descripcion_ed" name="descripcion" class="form-control"><?php echo $producto_editar['descripcion']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio_ed">Precio</label>
                <input id="precio_ed" type="number" step="0.01" name="precio" class="form-control" value="<?php echo $producto_editar['precio']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock_ed">Stock</label>
                <input id="stock_ed" type="number" name="stock" class="form-control" value="<?php echo $producto_editar['stock']; ?>" required>
            </div>
            <button type="submit" name="editar" class="btn btn-warning">Actualizar</button>
            <a href="admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
        <?php } ?>

        <h3>Productos</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td>€<?php echo $row['precio']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                            <a href="?editar=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>