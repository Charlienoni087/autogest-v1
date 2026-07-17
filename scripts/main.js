
const sidebarMenu = document.getElementById('sidebarMenu');
const contentFrame = document.getElementById('contentFrame');
const toggleMenuBtn = document.getElementById('toggleMenuBtn');

toggleMenuBtn.addEventListener('click', () => {
    // Intercambia las clases para ocultar o mostrar con animación suave
    sidebarMenu.classList.toggle('hidden');
    contentFrame.classList.toggle('expanded');
});



const alerta = document.getElementById('alertaExito');
    if (alerta) {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
            bsAlert.close();
        }, 3000);

        const url = new URL(window.location);
        url.searchParams.delete('save_success');
        window.history.replaceState({}, '', url);
    }

const loader = document.getElementById("loaderModulo");
const contenido = document.getElementById("contenidoModulo");

document.querySelectorAll(".btn-nav").forEach(btn => {

    btn.addEventListener("click", function (e) {

        e.preventDefault();

        const destino = this.href;

        contenido.style.transition = "opacity 0.3s ease";
        contenido.style.opacity = "0";

        setTimeout(() => {

            contenido.style.display = "none";
            loader.style.display = "flex";

            setTimeout(() => {
                window.location.href = destino;
            }, 1000);

        }, 300);

    });

});