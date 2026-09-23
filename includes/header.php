<?php
/** header.php — cabecera común del sitio */
if (!isset($PAGE_TITLE)) { $PAGE_TITLE = 'Calculadora de Ratios · Factorio Space Age'; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Calculadora visual de ratios para Factorio: Space Age. Máquinas, cintas y materia prima al instante.">
    <title><?= htmlspecialchars($PAGE_TITLE) ?></title>

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="fa-header py-3 mb-4">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h1>⚙️ Calculadora de Ratios <span class="badge badge-sa px-2">Space Age</span></h1>
                <p class="lead">Máquinas, cintas y materia prima al instante — para hacer tus builds como el de la imagen.</p>
            </div>
            <nav class="anchor-nav d-none d-md-flex gap-3">
                <a href="#calculadora">Calculadora</a>
                <a href="#referencia">Ratios</a>
                <a href="#como-funciona">Cómo funciona</a>
            </nav>
        </div>
    </header>
    <main class="container pb-5">
