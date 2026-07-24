document.addEventListener("DOMContentLoaded", () => {

    const loader = document.getElementById("loader");
    const contenido = document.getElementById("contenido");

    setTimeout(() => {

        loader.classList.add("fade");

        setTimeout(() => {

            loader.style.display = "none";

            contenido.style.display = "block";

            contenido.classList.add("animate__animated","animate__fadeIn");

        },300);

    },2500);

});
