<?php

$manualUrl = 'https://docs.google.com/document/d/1QcuchIONUSOYaPYnGqP1ry_TBveRbLzcrcz8L0WGgqo/edit?usp=drivesdk';

$descargaUrl = 'https://drive.google.com/file/d/1B5UI-nsXE5bl6XePnEYTsqRN2z9Yy5yr/view?usp=sharing';

$manualLabel = 'Manual de usuario';

$logoUrl = '../assets/autogest-logo.png';

$imageUrl = 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=1200&q=80';

$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($manualUrl);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/manual.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title><?= $manualLabel ?> | AutoGest</title>
</head>


<body>

    <main class="manual-wrapper">

        <section class="manual-card">

            <div class="manual-cover">

                <img
                    src="<?= $imageUrl ?>"
                    alt="Manual de usuario AutoGest"
                >

                <div class="cover-overlay"></div>

            </div>


            <div class="manual-content">

                <div class="manual-brand">

                    <img
                        src="<?= $logoUrl ?>"
                        alt="AutoGest"
                    >

                </div>

                <h2> <b>
                    <?= $manualLabel ?>
                    </b>
                </h2>


                <p class="manual-description">
                    Consulta la guía completa para conocer el funcionamiento
                    del sistema AutoGest, aprender a utilizar cada módulo,
                    realizar los procedimientos correctamente y resolver
                    las dudas más comunes.
                </p>

                <div class="actions">

                    <a
                        class="btn primary"
                        href="<?= $manualUrl ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="bi bi-book"></i>
                        Abrir manual
                    </a>

                    <a
                        class="btn secondary"
                        href="<?= $descargaUrl ?>"
                        download
                    >
                        <i class="bi bi-download"></i>
                        Descargar PDF
                    </a>

                </div>

                <div class="qr-box">

                    <div class="qr-image-wrapper">

                        <img
                            src="<?= $qrUrl ?>"
                            alt="Código QR del manual"
                        >

                    </div>

                    <div class="qr-content">

                        <strong>
                            Acceso desde tu teléfono
                        </strong>

                        <p>
                            Escanea el código QR para abrir el manual
                            directamente desde tu dispositivo móvil.
                        </p>

                    </div>

                </div>

            </div>

        </section>

    </main>

</body>

</html>