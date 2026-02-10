<?php
// Hola! Aquí empieza la página de administración.
// `session_start()` abre la sesión para saber quién está conectado.
session_start();
// Incluimos la configuración de la base de datos para poder usar `$conn`.
include '../php/config.php';

// Esto comprueba si la persona que abrió la página es admin.
// Si no es admin, la mandamos a iniciar sesión.
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Si el formulario de "Subir Producto" se ha enviado, aquí lo guardamos.
// Piensa: alguien rellena el formulario, pulsa "Subir" y los datos llegan aquí.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir'])) {
    // Guardamos lo que la persona puso en variables sencillas.
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    // La imagen llega como archivo, la convertimos para guardar en la base.
    $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    $imagen_tipo = $_FILES['imagen']['type'];

    // Aquí hacemos la consulta segura para insertar en la tabla productos.
    // Usamos prepared statements para mayor seguridad (evita inyección SQL).
    // bind_param necesita decir qué tipo tiene cada valor: s=string, d=double, i=int
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen, imagen_tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $nombre, $descripcion, $precio, $stock, $imagen, $imagen_tipo);
    $stmt->execute();
}

// Si se pide editar (por ejemplo al pulsar el enlace Editar),
// cargamos los datos del producto para mostrarlos en el formulario.
// Esto permite que el admin vea los valores actuales y los cambie.
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $producto_editar = $stmt->get_result()->fetch_assoc();
}

// Si el formulario de "Editar" se envía, actualizamos el producto.
// Aquí también comprobamos si el stock es 0; si lo es, marcamos el producto como inactivo.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = intval($_POST['stock']);

    // Si no hay stock, el producto pasa a inactivo para que nadie lo compre.
    $activo = ($stock <= 0) ? 0 : 1;

    // Actualizamos la fila en la base con los nuevos valores.
    // bind_param usa el orden y tipos correctos para prevenir errores.
    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, activo = ? WHERE id = ?");
    $stmt->bind_param("ssdiii", $nombre, $descripcion, $precio, $stock, $activo, $id);
    $stmt->execute();

    // Si quedó inactivo, quitamos ese producto de los carritos para que no se pueda comprar.
    if ($activo == 0) {
        $stmt = $conn->prepare("DELETE FROM carrito_productos WHERE producto_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    // Volvemos al listado para ver los cambios.
    header("Location: admin.php");
    exit();
}

// Cuando alguien pulsa "Eliminar" no borramos el producto totalmente.
// En vez de eso lo marcamos como inactivo (soft-delete) para no romper otras tablas.
// Esto es importante: si borráramos en cascada podríamos perder datos de pedidos.
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $stmt = $conn->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id_eliminar);
    $stmt->execute();
    // Y quitamos ese producto de los carritos por si alguien lo tenía añadido.
    $stmt = $conn->prepare("DELETE FROM carrito_productos WHERE producto_id = ?");
    $stmt->bind_param("i", $id_eliminar);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// Si queremos volver a usar un producto que estaba inactivo, lo restauramos.
if (isset($_GET['restaurar'])) {
    $id_restaurar = intval($_GET['restaurar']);
    $stmt = $conn->prepare("UPDATE productos SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id_restaurar);
    $stmt->execute();
    header("Location: admin.php?mostrar=inactivos");
    exit();
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Decidimos si mostramos los productos activos o los inactivos.
$mostrar = isset($_GET['mostrar']) && $_GET['mostrar'] === 'inactivos' ? 'inactivos' : 'activos';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Preparamos la consulta para obtener los productos según lo que queremos ver.
$activo_flag = ($mostrar === 'inactivos') ? 0 : 1;
if ($q !== '') {
    // Si hay búsqueda, usamos LIKE para buscar por nombre.
    $like = "%" . $q . "%";
    $stmt = $conn->prepare("SELECT id, nombre, descripcion, precio, stock FROM productos WHERE activo = ? AND nombre LIKE ? ORDER BY id DESC");
    $stmt->bind_param("is", $activo_flag, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Si no hay búsqueda, devolvemos todos los productos según 'activo'.
    $stmt = $conn->prepare("SELECT id, nombre, descripcion, precio, stock FROM productos WHERE activo = ? ORDER BY id DESC");
    $stmt->bind_param("i", $activo_flag);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Cuando la página pide solo las filas (ajax=1), devolvemos filas sueltas <tr>.
// Esto ayuda a que la búsqueda sea "en vivo" sin recargar toda la página.
// También usamos htmlspecialchars para evitar que alguien meta código peligroso (XSS).
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    while ($row = $result->fetch_assoc()) {
        $id = intval($row['id']);
        $nombre = htmlspecialchars($row['nombre'], ENT_QUOTES);
        $descripcion = htmlspecialchars($row['descripcion'], ENT_QUOTES);
        $precio = htmlspecialchars($row['precio'], ENT_QUOTES);
        $stock = htmlspecialchars($row['stock'], ENT_QUOTES);
        echo "<tr>";
        echo "<td>{$id}</td>";
        echo "<td>{$nombre}</td>";
        echo "<td>{$descripcion}</td>";
        echo "<td>€{$precio}</td>";
        echo "<td>{$stock}</td>";
        echo "<td class=\"crud-actions\">";
        if ($mostrar === 'inactivos') {
            echo "<a href=\"?restaurar={$id}\" class=\"btn btn-success btn-sm\">Restaurar</a>";
        } else {
            echo "<a href=\"?editar={$id}\" class=\"btn btn-sm btn-edit\">Editar</a>";
            echo " <a href=\"?eliminar={$id}\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm('¿Eliminar este producto?')\">Eliminar</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    // Terminamos aquí si la petición era AJAX, no mostramos la página completa.
    exit();
}
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
    <!-- Aquí se carga la barra de navegación (logo, enlaces, usuario). -->
    <?php include '../plantillas/nav.php'; ?>
    <div class="container mt-5">
        <h2 class="admin-title">Panel de Admin</h2>

        <div class="card admin-card p-4 mb-4">
            <!-- FORMULARIO: Subir Producto nuevo -->
            <h3 class="h5">Subir Producto</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre">Nombre</label>
                    <!-- Escribe aquí el nombre del producto -->
                    <input id="nombre" type="text" name="nombre" class="form-control" required>
                </div>
            <div class="mb-3">
                <label for="descripcion">Descripción</label>
                <!-- Descripción corta que se verá en la tienda -->
                <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio">Precio</label>
                <!-- Precio en euros, por ejemplo 12.50 -->
                <input id="precio" type="number" step="0.01" name="precio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock">Stock</label>
                <!-- Cantidad disponible en inventario. Si pones 0 el producto quedará inactivo. -->
                <input id="stock" type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="imagen">Imagen</label>
                <!-- Sube una imagen del producto. -->
                <input id="imagen" type="file" name="imagen" class="form-control" required>
            </div>
                <!-- Botón para enviar el formulario y crear el producto -->
                <button type="submit" name="subir" class="btn btn-primary btn-subir">Subir</button>
            </form>
        </div>

        <?php if (isset($producto_editar)) { ?>
        <div class="card admin-card p-4 mb-4">
            <!-- FORMULARIO: Editar Producto seleccionado -->
            <h3 class="h5">Editar Producto</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $producto_editar['id']; ?>">
                <div class="mb-3">
                    <label for="nombre_ed">Nombre</label>
                    <!-- Cambia el nombre y pulsa Actualizar para guardar -->
                    <input id="nombre_ed" type="text" name="nombre" class="form-control" value="<?php echo $producto_editar['nombre']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion_ed">Descripción</label>
                    <!-- Cambia la descripción aquí -->
                    <textarea id="descripcion_ed" name="descripcion" class="form-control"><?php echo $producto_editar['descripcion']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio_ed">Precio</label>
                    <!-- Ajusta el precio si hace falta -->
                    <input id="precio_ed" type="number" step="0.01" name="precio" class="form-control" value="<?php echo $producto_editar['precio']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stock_ed">Stock</label>
                    <!-- Cambia el stock. Si lo pones a 0, el producto se ocultará de la tienda. -->
                    <input id="stock_ed" type="number" name="stock" class="form-control" value="<?php echo $producto_editar['stock']; ?>" required>
                </div>
                <!-- Actualiza guarda los cambios; Cancelar vuelve al listado. -->
                <button type="submit" name="editar" class="btn btn-warning btn-actualizar">Actualizar</button>
                <a href="admin.php" class="btn btn-secondary btn-cancelar">Cancelar</a>
            </form>
        </div>
        <?php } ?>

        <div class="card admin-card p-4">
            <h3 class="h5">Productos</h3>
            <!-- Botones para ver productos activos o inactivos -->
            <?php /* botones para alternar vista activos/inactivos */ ?>
            <div class="mb-3">
                <a href="admin.php?mostrar=activos" class="btn btn-outline-secondary btn-sm <?php echo ($mostrar === 'activos') ? 'active' : ''; ?>">Activos</a>
                <a href="admin.php?mostrar=inactivos" class="btn btn-outline-secondary btn-sm <?php echo ($mostrar === 'inactivos') ? 'active' : ''; ?>">Inactivos</a>
            </div>

            <?php /* campo de búsqueda por nombre */ ?>
            <!-- Campo de búsqueda: escribe y la tabla se actualizará en vivo -->
            <form class="d-flex mb-3 align-items-center" method="GET" action="admin.php">
                <input type="hidden" name="mostrar" value="<?php echo $mostrar; ?>">
                <input name="q" class="form-control form-control-sm me-2" type="search" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars(isset($_GET['q'])?$_GET['q']:'', ENT_QUOTES); ?>">
                <button class="btn btn-outline-primary btn-sm me-2" type="submit">Buscar</button>
                <a href="admin.php?mostrar=<?php echo $mostrar; ?>" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </form>
            <div class="table-responsive">
                <!-- Tabla que muestra los productos. Las filas vienen de la base de datos. -->
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
                        <!-- Los datos que ves aquí vienen de cada fila de la tabla productos -->
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td>€<?php echo $row['precio']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td class="crud-actions">
                            <!-- Acciones: Editar abre el formulario arriba; Eliminar lo marca inactivo; Restaurar devuelve a activo -->
                            <?php if ($mostrar === 'inactivos') { ?>
                                <a href="?restaurar=<?php echo $row['id']; ?>" class="btn btn-success btn-sm btn-restaurar">Restaurar</a>
                            <?php } else { ?>
                                <a href="?editar=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Editar</a>
                                <a href="?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-eliminar" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
                </table>
            </div>
            <!-- Nota: borrado real desactivado para no romper pedidos/carritos. Usamos soft-delete. -->
            <small class="text-muted">Eliminar deshabilitado: los productos son elementos padre en otras tablas.</small>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
    (function(){
        const input = document.querySelector('input[name="q"]');
        const tbody = document.querySelector('.admin-card table tbody');
        if (!input || !tbody) return;
        const mostrar = '<?php echo $mostrar; ?>';
        let timer = null;
        // Live-search: cuando escribes, esperamos 300ms y pedimos al servidor las filas.
        // Luego reemplazamos el cuerpo de la tabla con lo que nos devuelva (solo <tr>).
        input.addEventListener('input', function(){
            clearTimeout(timer);
            timer = setTimeout(function(){
                const q = input.value;
                const params = new URLSearchParams({ajax: 1, mostrar: mostrar, q: q});
                fetch('admin.php?'+params.toString())
                .then(resp => resp.text())
                .then(html => {
                    // Ponemos las nuevas filas dentro de la tabla para que se vea en vivo.
                    tbody.innerHTML = html;
                })
                .catch(err => console.error('Live search error', err));
            }, 300);
        });
    })();
    </script>
</body>
</html>