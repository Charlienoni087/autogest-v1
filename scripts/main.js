
document.addEventListener('DOMContentLoaded', function () {
    const sidebarMenu = document.getElementById('sidebarMenu');
    const contentFrame = document.getElementById('contentFrame');
    const toggleMenuBtn = document.getElementById('toggleMenuBtn');

    if (sidebarMenu && contentFrame && toggleMenuBtn) {
        toggleMenuBtn.addEventListener('click', () => {
            sidebarMenu.classList.toggle('hidden');
            contentFrame.classList.toggle('expanded');
        });
    }

    const modalLogoutElement = document.getElementById('modalLogout');
    const btnLogout = document.getElementById('btnLogout');

    if (modalLogoutElement && btnLogout) {
        const bootstrapDisponible = typeof bootstrap !== 'undefined' && bootstrap.Modal;
        const modalLogout = bootstrapDisponible
            ? bootstrap.Modal.getOrCreateInstance(modalLogoutElement)
            : null;

        btnLogout.addEventListener('click', function (event) {
            event.preventDefault();
            if (modalLogout) {
                modalLogout.show();
                return;
            }

            modalLogoutElement.classList.add('show');
            modalLogoutElement.style.display = 'block';
            modalLogoutElement.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');
        });

        modalLogoutElement.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
            button.addEventListener('click', function () {
                if (modalLogout) {
                    return;
                }

                modalLogoutElement.classList.remove('show');
                modalLogoutElement.style.display = 'none';
                modalLogoutElement.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
            });
        });
    }
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

document.querySelectorAll(".btn-nav:not(#btnLogout)").forEach(btn => {
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
