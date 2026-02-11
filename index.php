<?php
include 'php/config.php';

/*
 * Cookie: contadorEntradas
 * - Mantener un contador del número de veces que se ha cargado la página principal
 *   en este equipo/cliente. No es un contador global,
 *   solo local al navegador que visita (se almacena en una cookie del cliente)
 * - Si ya existe la cookie se incrementa en 1, si no existe
 *   se crea con valor 1.
 * - Caducidad: 10 años (se configura largo para que esté "siempre presente")
 */
$contadorName = 'contadorEntradas';
if (isset($_COOKIE[$contadorName])) {
    $contador = intval($_COOKIE[$contadorName]) + 1;
} else {
    $contador = 1;
}
// Caducidad larga para que esté "siempre presente" (10 años)
setcookie($contadorName, (string)$contador, time() + (10 * 365 * 24 * 60 * 60), "/");


/*
 * Cookie: userName
 * - Almacenar una usuario (nombre o username)
 * - Solo se crea/actualiza si el usuario tiene sesión activa (`$_SESSION['user_id']`)
 * - Caducidad: 30 días. No contiene credenciales ni información sensible, solo userName
 */
$userCookieName = 'userName';
if (isset($_SESSION['user_id'])) {
    $uid = intval($_SESSION['user_id']);
    if (isset($conn)) {
        $stmt = $conn->prepare("SELECT nombre, username FROM usuarios WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $uid);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $u = $res->fetch_assoc();
                $name = !empty($u['nombre']) ? $u['nombre'] : $u['username'];
                // Cookie duración razonable (30 días)
                setcookie($userCookieName, $name, time() + (30 * 24 * 60 * 60), "/");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Página principal de la web de Manucho 27, artista del mundillo del hip hop. En esta, podrás consultar información sobre el y su música. Disfruta"/>
    <meta name="keywords" content="Manucho 27, Manucho, Hip Hop, Rap, Artista, Música, Álbumes, Canciones">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'plantillas/iconos.php'; ?>
    <title>Manucho 27 - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="js/darkTheme.js"></script>
</head>

<body>
    <?php include 'plantillas/nav.php'; ?>
    <div id="pag_principal" class="container-fluid min-vh-100 p-0">
        <!-- Fondo mediante CSS: ../img/Manu/P1210213.JPG -->
        <div class="row w-100 m-0">
            <div id="texto" class="col-12 col-md-3 offset-md-8 d-flex flex-column align-items-center pt-vh-20 pt-vh-md-60 pb-pct-13">
                <h1>MANUCHO 27</h1>
                <div id="bprin" class="d-flex justify-content-around w-75">
                    <a class="spoti" href="https://open.spotify.com/intl-es/artist/27K3MUgWcpbPj4RSYNxcew?si=709fe9d280204d84" target="_blank">SPOTIFY</a>
                    <a class="sm" href="html/sobre-mi.php">SOBRE MÍ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- TEMPLATE (no se ve en pantalla) -->
    <template id="tarjeta-red-social">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="tarjeta shadow-sm red-social-tarjeta">
                <img class="tarjeta-img-top imgredes" src="" alt="">
                <div class="tarjeta-body p-3 text-center">
                    <p class="mb-2"></p>
                    <div class="logoredes mb-2">
                        <img src="" alt="">
                    </div>
                    <a class="btn btn-primary btn-sm px-4 rounded-pill" href="" target="_blank">Escuchar</a>
                </div>
            </div>
        </div>
    </template>

    <div id="contenedor-redes" class="row m-0 gx-3 gy-4 px-2"></div>
    <script src="plantillas/tarjetaRedes.js"></script>

    <?php include 'plantillas/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>