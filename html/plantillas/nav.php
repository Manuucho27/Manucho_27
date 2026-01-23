<nav id="menu" class="navbar navbar-expand-lg">
    <?php
    $path = parse_url(
        // REQUEST_URI puede incluir querystrings; nos quedamos con la ruta
        isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '' ,
        PHP_URL_PATH
    );
    $pagina_actual = basename($path);

    function activo($pagina, $actual) {
        return $pagina === $actual ? 'active' : '';
    }

    function activoEntre($paginas, $actual) {
        return in_array($actual, $paginas) ? 'active' : '';
    }
    ?>
    <div class="container-fluid">
        <a class="navbar-brand" href="/Manucho27/index.php"><img id="logo_menu" src="/Manucho27/img/iconos/favicon.png" alt="">Manucho 27</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo activo('index.php', $pagina_actual); ?>" href="/Manucho27/index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo activo('sobre-mi.php', $pagina_actual); ?>" href="/Manucho27/html/sobre-mi.php">Sobre mi</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo activoEntre(array('prisionero.php','una-chica.php','disfruto.php','vimos-madriz.php','si-responde-mis-stories.php','una-vez-alli.php','amor-caducado.php','fueron.php','ponte-cascos.php'), $pagina_actual); ?>" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Temas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo activo('prisionero.php', $pagina_actual); ?>" href="/Manucho27/html/prisionero.php">Prisionero</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item <?php echo activo('una-chica.php', $pagina_actual); ?>" href="/Manucho27/html/una-chica.php">Una chica</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item <?php echo activo('disfruto.php', $pagina_actual); ?>" href="/Manucho27/html/disfruto.php">Disfruto</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item <?php echo activo('vimos-madriz.php', $pagina_actual); ?>" href="/Manucho27/html/vimos-madriz.php">Vimos Madriz</a></li>
                        <li><a class="dropdown-item <?php echo activo('si-responde-mis-stories.php', $pagina_actual); ?>" href="/Manucho27/html/si-responde-mis-stories.php">Si responde mis stories</a></li>
                        <li><a class="dropdown-item <?php echo activo('una-vez-alli.php', $pagina_actual); ?>" href="/Manucho27/html/una-vez-alli.php">Una vez allí</a></li>
                        <li><a class="dropdown-item <?php echo activo('amor-caducado.php', $pagina_actual); ?>" href="/Manucho27/html/amor-caducado.php">Amor caducado</a></li>
                        <li><a class="dropdown-item <?php echo activo('fueron.php', $pagina_actual); ?>" href="/Manucho27/html/fueron.php">Fueron</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item <?php echo activo('ponte-cascos.php', $pagina_actual); ?>" href="/Manucho27/html/ponte-cascos.php">Ponte cascos</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo activo('tienda.php', $pagina_actual); ?>" href="/Manucho27/html/tienda.php">Tienda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo activo('contacto.php', $pagina_actual); ?>" href="/Manucho27/html/contacto.php">Contacto</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accesibilidadDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Accesibilidad
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accesibilidadDropdown">
                        <li><a class="dropdown-item" href="#" id="aumentar-texto">Aumentar texto</a></li>
                        <li><a class="dropdown-item" href="#" id="disminuir-texto">Disminuir texto</a></li>
                        <li><a class="dropdown-item" href="#" id="alto-contraste">Alto contraste</a></li>
                        <li><a class="dropdown-item" href="#" id="contraste-normal">Contraste normal</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" id="modo-oscuro" onclick="cambiaModoColor()">Cambiar a modo oscuro</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>