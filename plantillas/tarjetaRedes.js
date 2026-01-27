const data = [
    {
        id: "spotify",
        img: "fotos/P1210180.JPG",
        texto: "Escúchame en",
        logo: "fotos/lspoti.png",
        link: "https://open.spotify.com/intl-es/artist/27K3MUgWcpbPj4RSYNxcew"
    },
    {
        id: "instagram",
        img: "fotos/foto2.jpg",
        texto: "Sígueme en",
        logo: "fotos/linstagram.png",
        link: "https://instagram.com"
    },
    {
        id: "youtube",
        img: "fotos/foto3.jpg",
        texto: "Mírame en",
        logo: "fotos/lyoutube.png",
        link: "https://www.youtube.com/@manucho27"
    },
    {
        id: "twitter",
        img: "fotos/foto4.jpg",
        texto: "Sígueme en",
        logo: "fotos/ltwitter.png",
        link: "https://twitter.com"
    },
    {
        id: "facebook",
        img: "fotos/foto5.jpg",
        texto: "Dale me gusta en",
        logo: "fotos/lfacebook.png",
        link: "https://facebook.com"
    },
    {
        id: "tiktok",
        img: "fotos/foto6.jpg",
        texto: "Búscame en",
        logo: "fotos/ltiktok.png",
        link: "https://tiktok.com"
    },
    {
        id: "tidal",
        img: "fotos/foto7.jpg",
        texto: "Escúchame en",
        logo: "fotos/ltidal.png",
        link: "https://tidal.com"
    }
];

const container = document.getElementById("contenedor-redes");
const template = document.getElementById("tarjeta-red-social");

data.forEach(item => {
    const clone = template.content.cloneNode(true);

    clone.querySelector(".imgredes").src = item.img;
    clone.querySelector("p").textContent = item.texto;
    clone.querySelector(".logoredes img").src = item.logo;
    clone.querySelector("a").href = item.link;
    clone.querySelector(".red-social").id = item.id;

    container.appendChild(clone);
});