const data = [
    {
        id: "spotify",
        img: "img/Manu/P1210180.JPG",
        texto: "Escúchame en",
        logo: "img/logos/lspoti.png",
        link: "https://open.spotify.com/intl-es/artist/27K3MUgWcpbPj4RSYNxcew"
    },
    {
        id: "ig",
        img: "img/Manu/P1210181.JPG",
        texto: "Sígueme en",
        logo: "img/logos/lig.webp",
        link: "https://instagram.com"
    },
    {
        id: "yt",
        img: "img/Manu/P1210185.JPG",
        texto: "Mírame en",
        logo: "img/logos/lyt.png",
        link: "https://www.youtube.com/@manucho27"
    },
    {
        id: "x",
        img: "img/Manu/P1210189.JPG",
        texto: "Sígueme en",
        logo: "img/logos/lx.png",
        link: "https://twitter.com"
    },
    {
        id: "fb",
        img: "img/Manu/P1210191.JPG",
        texto: "Dale me gusta en",
        logo: "img/logos/lfb.png",
        link: "https://facebook.com"
    },
    {
        id: "tiktok",
        img: "img/Manu/foto6.jpg",
        texto: "Búscame en",
        logo: "img/logos/ltt.webp",
        link: "https://tiktok.com"
    },
    {
        id: "tidal",
        img: "img/Manu/foto7.jpg",
        texto: "Escúchame en",
        logo: "img/logos/ltidal.png",
        link: "https://tidal.com"
    },
    {
        id: "am",
        img: "img/Manu/foto8.jpg",
        texto: "Escúchame en",
        logo: "img/logos/lam.png",
        link: "https://music.apple.com"
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

    const tarjetaElem = clone.querySelector(".tarjeta");

    if(img) img.src = item.img;
    if(texto) texto.textContent = item.texto;
    if(logoImg) logoImg.src = item.logo;
    if(link) link.href = item.link;
    if(tarjetaElem) tarjetaElem.id = item.id;

    container.appendChild(clone);
});