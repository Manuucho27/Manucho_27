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

    <div id="contenedor-redes" class="row gx-3 gy-4 px-2"></div>
    <script src="plantillas/tarjetaRedes.js"></script>

<!--
    <div id="redes">
        <div id="falta">
            <div id="spotify">
                <img class="imgredes" src="fotos/P1210180.JPG" alt="">
                <p>Escúchame en </p>
                <div class="logoredes">
                    <img src="fotos/lspoti.png" alt="">
                </div>
                <a href="https://open.spotify.com/intl-es/artist/27K3MUgWcpbPj4RSYNxcew?si=709fe9d280204d84" target="_blank">Escuchar</a>
            </div>
            <div id="yt">
                <img class="imgredes" src="fotos/P1210181.JPG" alt="">
                <p>Escúchame en </p>
                <div class="logoredes">
                    <img src="fotos/lyt.png" alt="">
                </div>
                <a href="https://www.youtube.com/channel/UCXFnGAHp5vZL1BHOqdyc2vw" target="_blank">Escuchar</a>
            </div>
            <div id="ig">
                <img class="imgredes" src="fotos/P1210185.JPG" alt="">
                <p>Sígueme en </p>
                <div class="logoredes">
                    <img src="fotos/lig.webp" alt="">
                </div>
                <a href="https://www.instagram.com/manu.uu27/" target="_blank">Seguir</a>
            </div>
            <div id="am">
                <img class="imgredes" src="fotos/P1210189.JPG" alt="">
                <p>Escúchame en </p>
                <div class="logoredes">
                    <img class="bordeb" src="fotos/lam.png" alt="">
                </div>
                <a href="https://music.apple.com/es/artist/manucho-27/1706972723" target="_blank">Escuchar</a>
            </div>
        </div>
        <div id="fbajo">
            <div id="tidal">
                <img class="imgredes" src="fotos/P1210193.JPG" alt="">
                <p>Escúchame en </p>
                <div class="logoredes">
                    <img class="bordeb" src="fotos/ltidal.png" alt="">
                </div>
                <a href="https://tidal.com/browse/artist/42021329" target="_blank">Escuchar</a>
            </div>
            <div id="fb">
                <img class="imgredes" src="fotos/P1210191.JPG" alt="">
                <p>Sígueme en </p>
                <div class="logoredes">
                    <img src="fotos/lfb.png" alt="">
                </div>
                <a href="https://es-es.facebook.com/people/Manuel-Moreno-Bell%C3%B3n/pfbid02eZWvkugrWA61jGsdrDkxz7LJ8QKx32g4bA1qxsP5xPER6cH4NuSFekEYSTHG1kSWl/" target="_blank">Seguir</a>
            </div>
            <div id="x">
                <img class="imgredes" src="fotos/P1210192.JPG" alt="">
                <p>Sígueme en </p>
                <div class="logoredes">
                    <img class="borden" src="fotos/lx.png" alt="">
                </div>
                <a href="https://twitter.com/manucho_27" target="_blank">Seguir</a>
            </div>
        </div>
    </div> 
-->

    <?php include 'plantillas/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>