<?php
/**
 * index.php — Calculadora de Ratios de Factorio: Space Age
 * Estructura: data.php (datos) -> header -> contenido -> footer.
 */
require __DIR__ . '/includes/data.php';
$PAGE_TITLE = 'Calculadora de Ratios · Factorio Space Age';
require __DIR__ . '/includes/header.php';
?>

<!-- ============================ CALCULADORA ============================ -->
<section id="calculadora" class="row g-4 mb-5">
    <!-- Panel de entrada -->
    <div class="col-lg-5">
        <div class="fa-panel h-100">
            <div class="fa-panel-head"><span class="num">1.</span> ¿Qué quieres producir?</div>
            <div class="p-3">
                <form id="fa-form">
                    <div class="mb-3">
                        <label class="form-label" for="fa-product">Producto</label>
                        <select id="fa-product" class="form-select">
                            <?php foreach ($PRODUCT_GROUPS as $group => $keys): ?>
                                <optgroup label="<?= htmlspecialchars($group) ?>">
                                    <?php foreach ($keys as $k): ?>
                                        <option value="<?= $k ?>" <?= $k === 'electronic_circuit' ? 'selected' : '' ?>>
                                            <?= $ITEMS[$k]['e'] . ' ' . htmlspecialchars($ITEMS[$k]['n']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label" for="fa-rate">Cantidad objetivo</label>
                            <input type="number" id="fa-rate" class="form-control" value="60" min="0" step="any">
                        </div>
                        <div class="col-5">
                            <label class="form-label d-block">Unidad</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="unit" id="u-s" value="s" checked>
                                <label class="btn btn-outline-fa" for="u-s">/ s</label>
                                <input type="radio" class="btn-check" name="unit" id="u-min" value="min">
                                <label class="btn btn-outline-fa" for="u-min">/ min</label>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color:var(--fa-border)">

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="fa-furnace">Tipo de horno</label>
                            <select id="fa-furnace" class="form-select">
                                <option value="electric" selected>Eléctrico (x2)</option>
                                <option value="steel">De acero (x2)</option>
                                <option value="stone">De piedra (x1)</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="fa-assembler">Ensambladora</label>
                            <select id="fa-assembler" class="form-select">
                                <option value="1">Nivel 1 (x0.5)</option>
                                <option value="2">Nivel 2 (x0.75)</option>
                                <option value="3" selected>Nivel 3 (x1.25)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-block">Cinta para el cálculo</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="belt" id="b-y" value="yellow" checked>
                                <label class="btn btn-outline-fa" for="b-y">Amarilla 15/s</label>
                                <input type="radio" class="btn-check" name="belt" id="b-r" value="red">
                                <label class="btn btn-outline-fa" for="b-r">Roja 30/s</label>
                                <input type="radio" class="btn-check" name="belt" id="b-b" value="blue">
                                <label class="btn btn-outline-fa" for="b-b">Azul 45/s</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fa w-100 mt-4">Calcular ratios ⚙️</button>
                    <p class="text-center mt-2 mb-0"><small class="empty-hint">Se recalcula solo al cambiar cualquier dato.</small></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Panel de resultados -->
    <div class="col-lg-7">
        <div class="fa-panel h-100">
            <div class="fa-panel-head"><span class="num">2.</span> Lo que necesitas</div>
            <div class="p-3" id="fa-results">
                <p class="empty-hint">Ajusta los datos y verás aquí las máquinas, cintas y materia prima.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================ REFERENCIA ============================ -->
<section id="referencia" class="mb-5">
    <h2 class="mb-3" style="font-weight:800;letter-spacing:.4px">📋 Ratios de referencia</h2>
    <p class="empty-hint mb-4">Los ratios clásicos que conviene memorizar (los mismos que muestran los videos y el cheat sheet).</p>
    <div class="row g-3">
        <?php foreach ($RATIO_CARDS as $card): ?>
            <div class="col-md-6 col-xl-4">
                <div class="fa-panel ref-card">
                    <div class="fa-panel-head">
                        <span><?= $card['emoji'] ?> <?= htmlspecialchars($card['title']) ?></span>
                    </div>
                    <div class="p-3">
                        <p class="ratio-big mb-2"><?= htmlspecialchars($card['ratio']) ?></p>
                        <p class="body-txt"><?= $card['body'] // HTML controlado en data.php ?></p>
                        <?php foreach ($card['rows'] as $row): ?>
                            <div class="row-line">
                                <b><?= htmlspecialchars($row[0]) ?></b>
                                <span><?= htmlspecialchars($row[1]) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================ CÓMO FUNCIONA ============================ -->
<section id="como-funciona" class="mb-4">
    <h2 class="mb-3" style="font-weight:800;letter-spacing:.4px">🧮 Cómo funciona la matemática</h2>
    <div class="fa-panel">
        <div class="p-3 p-md-4">
            <p>Todo ratio en Factorio sale de una sola fórmula. Cada receta produce una cantidad
               en un tiempo fijo, y cada máquina trabaja a una velocidad. El número de máquinas es:</p>
            <div class="formula mb-3">
                nº máquinas = ( <span class="hl">objetivo/s</span> ×
                <span class="hl">tiempo receta</span> ) ÷ (
                <span class="hl">cantidad producida</span> ×
                <span class="hl">velocidad máquina</span> )
            </div>
            <p class="mb-2"><b>Ejemplo — circuito verde a 60/s (ensambladora nivel 3, x1.25):</b></p>
            <ul class="mb-3" style="color:var(--fa-muted)">
                <li>Receta: 1 circuito cada <b>0.5 s</b>, produce <b>1</b>. →
                    (60 × 0.5) ÷ (1 × 1.25) = <b style="color:var(--fa-orange)">24 ensambladoras</b> de circuito.</li>
                <li>Cada circuito usa <b>3 cables</b> → 180 cables/s. El cable (0.5 s, produce 2) →
                    (180 × 0.5) ÷ (2 × 1.25) = <b style="color:var(--fa-orange)">36 ensambladoras</b> de cable... que se reduce
                    al ratio <b>3:2</b> respecto a los circuitos (36:24).</li>
                <li>Ese <b>3:2</b> es exactamente el build de tu imagen: 3 máquinas de cable por cada 2 de circuito.</li>
            </ul>
            <p class="mb-0 empty-hint">La calculadora de arriba aplica esta fórmula recursivamente por toda la
               cadena, hasta llegar a la materia prima (mineral, carbón, agua, crudo).</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
