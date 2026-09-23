<?php
/** footer.php — pie común + inyección de datos a JS + scripts */
?>
    </main>

    <footer class="fa-footer py-4 mt-4">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>Hecho para planificar fábricas de <b>Factorio: Space Age</b>. Recetas verificadas contra la wiki oficial.</span>
            <span>Inspirado en <a href="https://kirkmcdonald.github.io/calc.html" target="_blank" rel="noopener">kirkmcdonald</a>
                  y <a href="https://factoriocheatsheet.com/" target="_blank" rel="noopener">factoriocheatsheet</a>.</span>
        </div>
    </footer>

    <!-- Datos desde PHP (fuente única de verdad) -->
    <script>
        window.FA_DATA = {
            items:   <?= json_encode($ITEMS, JSON_UNESCAPED_UNICODE) ?>,
            recipes: <?= json_encode($RECIPES, JSON_UNESCAPED_UNICODE) ?>,
            raw:     <?= json_encode($RAW, JSON_UNESCAPED_UNICODE) ?>
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/calc.js"></script>
</body>
</html>
