# Calculadora de Ratios — Factorio: Space Age

Sitio en **PHP + Bootstrap + JS** para calcular ratios de fábricas (máquinas, cintas y
materia prima) y consultar los ratios clásicos de referencia.

## Estructura
```
index.php              → página principal (calculadora + referencia + cómo funciona)
includes/
  data.php             → base de datos de recetas (FUENTE ÚNICA de verdad)
  header.php           → cabecera común
  footer.php           → pie + inyección de datos a JS + scripts
assets/
  css/style.css        → tema oscuro estilo Factorio
  js/calc.js           → motor de cálculo (todo en el navegador)
```

## Probar en local
Necesitas PHP:
```bash
php -S 127.0.0.1:8000
```
Abre http://127.0.0.1:8000 en el navegador.

## Subir a HostGator
1. Entra a **cPanel → Administrador de archivos** (o por FTP con FileZilla).
2. Ve a `public_html/` (o una subcarpeta, p. ej. `public_html/factorio/`).
3. Sube **todo el contenido de esta carpeta** manteniendo la estructura
   (`index.php`, `includes/`, `assets/`).
4. Visita `https://tudominio.com/` (o `/factorio/` si usaste subcarpeta).

HostGator ya trae PHP, así que funciona sin configurar nada más.
Bootstrap se carga por CDN (requiere que el visitante tenga internet, lo normal).

## Añadir o corregir recetas
Todo se edita en un solo sitio: **`includes/data.php`**.
- `$RECIPES` — recetas (tiempo en segundos, ingredientes y productos por craft).
- `$ITEMS` — nombre en español + emoji de cada ítem.
- `$PRODUCT_GROUPS` — qué aparece en el selector.
- `$RATIO_CARDS` — las tarjetas de referencia.

El navegador recibe estos datos automáticamente (no hay que tocar el JS).

## Notas de precisión
Recetas verificadas contra la wiki oficial y reconciliadas por costo hasta el crudo.
El módulo de petróleo asume **aceite avanzado + cracking completo** (19.5 gas/s por refinería).
Los ratios entre máquinas son independientes del nivel de ensambladora/horno; el número
absoluto de máquinas sí depende del tier elegido en los selectores.
