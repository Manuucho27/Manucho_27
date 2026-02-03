const data = [
    {
        id: "spotify",
        img: "img/Manu/P1210180.webp",
        texto: "Escúchame en",
        logo: "img/logos/lspoti.png",
        link: "https://open.spotify.com/intl-es/artist/27K3MUgWcpbPj4RSYNxcew",
        textoBtn: "Spotify"
    },
    {
        id: "ig",
        img: "img/Manu/P1210181.webp",
        texto: "Sígueme en",
        logo: "img/logos/lig.webp",
        link: "https://instagram.com/manuchoo27",
        textoBtn: "Instagram"
    },
    {
        id: "yt",
        img: "img/Manu/P1210252.webp",
        texto: "Mírame en",
        logo: "img/logos/lyt.png",
        link: "https://www.youtube.com/@manucho27",
        textoBtn: "YouTube"
    },
    {
        id: "x",
        img: "img/Manu/P1210192.webp",
        texto: "Sígueme en",
        logo: "img/logos/lx.png",
        link: "https://x.com/Manucho_27",
        textoBtn: "X"
    },
    {
        id: "fb",
        img: "img/Manu/P1210191.webp",
        texto: "Dale me gusta en",
        logo: "img/logos/lfb.png",
        link: "https://www.facebook.com/share/1AbWeTbi1A/",
        textoBtn: "Facebook"
    },
    {
        id: "tiktok",
        img: "img/Manu/P1210193.webp",
        texto: "Búscame en",
        logo: "img/logos/ltt.webp",
        link: "https://www.tiktok.com/@manuucho27",
        textoBtn: "TikTok"
    },
    {
        id: "tidal",
        img: "img/Manu/P1210203.webp",
        texto: "Escúchame en",
        logo: "img/logos/ltidal.webp",
        link: "https://tidal.com/artist/42021329",
        textoBtn: "Tidal"
    },
    {
        id: "am",
        img: "img/Manu/P1210242.webp",
        texto: "Escúchame en",
        logo: "img/logos/apple-logo.webp",
        link: "https://music.apple.com/es/artist/manucho-27/1706972723",
        textoBtn: "Apple Music"
    }
];

const container = document.getElementById("contenedor-redes");
const template = document.getElementById("tarjeta-red-social");

data.forEach(item => {
    const clone = template.content.cloneNode(true);

    const img = clone.querySelector(".imgredes");
    const texto = clone.querySelector("p");
    const logoImg = clone.querySelector(".logoredes img");
    const link = clone.querySelector("a");
    const btn = clone.querySelector(".btn");

    const tarjetaElem = clone.querySelector(".tarjeta");

    if(img) img.src = item.img;
    if(texto) texto.textContent = item.texto;
    if(btn) btn.textContent = item.textoBtn;
    if(logoImg) logoImg.src = item.logo;
    if(link) link.href = item.link;
    if(tarjetaElem) tarjetaElem.id = item.id;

    container.appendChild(clone);
});