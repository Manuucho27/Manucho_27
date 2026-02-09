<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manucho 27 - Tienda</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="../js/darkTheme.js"></script>
    <?php include '../plantillas/iconos.php'; ?>
</head>

<body>
    <?php
    include '../php/config.php';
    $result = $conn->query("SELECT * FROM productos");
    ?>
    <?php include '../plantillas/nav.php'; ?>

    <div class="container mt-5 tienda">
        <h1>Tienda</h1>
        <?php if (isset($_GET['pedido'])) echo "<div class='alert alert-success'>Pedido completado con éxito.</div>"; ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4 d-flex">
                    <div class="card w-100">
                        <img src="data:<?php echo $row['imagen_tipo']; ?>;base64,<?php echo base64_encode($row['imagen']); ?>" class="card-img-top" alt="<?php echo $row['nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text descripcion"><?php echo $row['descripcion']; ?></p>
                            <p class="card-text">Precio: <?php echo $row['precio']; ?>€</p>
                            <p class="card-text">Stock: <?php echo $row['stock']; ?></p>
                            <?php if (isset($_SESSION['user_id'])) { ?>
                                <form method="POST" action="carrito.php">
                                    <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-2">
                                                <label for="cantidad-<?php echo $row['id']; ?>">Cantidad</label>
                                                <input id="cantidad-<?php echo $row['id']; ?>" type="number" name="cantidad" value="1" min="1" max="<?php echo min(2, $row['stock']); ?>" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-primary" aria-label="Añadir <?php echo htmlspecialchars($row['nombre'], ENT_QUOTES); ?> al carrito">Añadir al Carrito</button>
                                </form>
                            <?php } else { ?>
                                <p>Inicia sesión para comprar.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include '../plantillas/footer.php'; ?>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>
</body>

</html>