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
                    <a class="sm" href="biografia.html">SOBRE MÍ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- TEMPLATE (no se ve en pantalla) -->
    <template id="tarjeta-red-social">
        <div class="red-social" id="red">
            <img class="imgredes" src="" alt="">
            <p></p>
            <div class="logoredes">
                <img src="" alt="">
            </div>
            <a href="" target="_blank">Escuchar</a>
        </div>
    </template>

    <div id="contenedor-redes"></div>
    <script src="plantillas/tarjetaRedes.js"></script>

    <?php include 'plantillas/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>