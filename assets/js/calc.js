/* ===========================================================================
   calc.js — Motor de ratios de Factorio: Space Age
   Recibe los datos desde PHP en window.FA_DATA = { items, recipes, raw }.
   Todo el cálculo es cliente (no recarga la página).
   =========================================================================== */
(function () {
    "use strict";

    const D = window.FA_DATA;
    const ITEMS   = D.items;
    const RECIPES = D.recipes;
    const RAW     = new Set(D.raw);

    /* ---- Velocidades de máquina según el tier elegido ---- */
    const FURNACE_SPEED = { stone: 1, steel: 2, electric: 2 };
    const ASSEMBLER_SPEED = { "1": 0.5, "2": 0.75, "3": 1.25 };
    const BELT_TP = { yellow: 15, red: 30, blue: 45 };

    const CAT_LABEL = {
        smelt:    "🔥 Hornos",
        assembly: "🏭 Ensambladoras",
        chem:     "⚗️ Plantas químicas",
    };

    /* -----------------------------------------------------------------------
       Constantes del módulo de petróleo (aceite avanzado + cracking completo).
       Deducidas por segundo y por refinería a velocidad 1:
         crudo 20/s -> pesado 5 · ligero 9 · gas 11
         cracking pesado 0.25 plantas · cracking ligero 0.85 plantas
         => 19.5 gas/s por refinería, 26.5 agua/s por refinería
    ----------------------------------------------------------------------- */
    const OIL = {
        gasPerRefinery:   19.5,
        crudePerRefinery: 20,
        heavyCrackPer:    0.25,
        lightCrackPer:    0.85,
        waterPerRefinery: 26.5,
    };

    /* ---- Utilidades ---- */
    function icon(key)  { return (ITEMS[key] && ITEMS[key].e) || "▫️"; }
    function name(key)  { return (ITEMS[key] && ITEMS[key].n) || key; }
    function fmt(n) {
        if (!isFinite(n)) return "0";
        if (n === 0) return "0";
        const r = Math.round(n * 100) / 100;
        return (r % 1 === 0) ? String(r) : r.toFixed(2);
    }

    function machineSpeed(cat, tiers) {
        if (cat === "smelt")    return FURNACE_SPEED[tiers.furnace];
        if (cat === "assembly") return ASSEMBLER_SPEED[tiers.assembler];
        return 1; // chem
    }

    /* -----------------------------------------------------------------------
       Solver: expande recursivamente el objetivo en máquinas, materia prima
       y flujos de cada ítem (para calcular cintas).
    ----------------------------------------------------------------------- */
    function solve(targetKey, ratePerSec, tiers) {
        const machines = {}; // recipeKey -> nº máquinas (fraccional)
        const raw      = {}; // itemKey -> unidades/s
        const flow     = {}; // itemKey -> unidades/s (producidas en total)

        function expand(itemKey, rate) {
            flow[itemKey] = (flow[itemKey] || 0) + rate;
            const r = RECIPES[itemKey];
            if (!r || RAW.has(itemKey)) {
                raw[itemKey] = (raw[itemKey] || 0) + rate;
                return;
            }
            const outQty = r.out[itemKey];
            const craftsPerSec = rate / outQty;
            const speed = machineSpeed(r.cat, tiers);
            machines[itemKey] = (machines[itemKey] || 0) + (craftsPerSec * r.t) / speed;
            for (const ing in r.in) {
                expand(ing, craftsPerSec * r.in[ing]);
            }
        }
        expand(targetKey, ratePerSec);
        return { machines, raw, flow };
    }

    /* -----------------------------------------------------------------------
       Render de resultados
    ----------------------------------------------------------------------- */
    function resRow(iconStr, nameStr, qtyHtml, subHtml) {
        const sub = subHtml ? `<div class="sub">${subHtml}</div>` : "";
        return `<div class="res-row">
            <div class="ico">${iconStr}</div>
            <div class="name">${nameStr}${sub}</div>
            <div class="qty">${qtyHtml}</div>
        </div>`;
    }

    function render(targetKey, ratePerSec, tiers, unitLabel) {
        const { machines, raw, flow } = solve(targetKey, ratePerSec, tiers);
        let html = "";

        /* -- Resumen -- */
        html += `<div class="mb-2">Para producir
            <span class="qty" style="color:var(--fa-orange);font-weight:800">
            ${icon(targetKey)} ${fmt(ratePerSec)} /s ${name(targetKey)}</span>
            <span class="pill">${fmt(ratePerSec * 60)} /min</span></div>`;

        /* -- Máquinas agrupadas por categoría -- */
        const groups = { smelt: [], assembly: [], chem: [] };
        Object.keys(machines).forEach((k) => {
            const cat = RECIPES[k].cat;
            groups[cat].push([k, machines[k]]);
        });
        ["smelt", "assembly", "chem"].forEach((cat) => {
            const list = groups[cat];
            if (!list.length) return;
            list.sort((a, b) => b[1] - a[1]);
            html += `<div class="result-group-title">${CAT_LABEL[cat]}</div>`;
            list.forEach(([k, count]) => {
                const q = `${Math.ceil(count - 1e-9)} <span class="exact">(${fmt(count)})</span>`;
                html += resRow(icon(k), name(k), q, null);
            });
        });

        /* -- Petróleo (si se consume gas) -- */
        const gas = raw.petroleum_gas || 0;
        if (gas > 0) {
            const ref   = gas / OIL.gasPerRefinery;
            const crude = ref * OIL.crudePerRefinery;
            const hc    = ref * OIL.heavyCrackPer;
            const lc    = ref * OIL.lightCrackPer;
            raw.crude_oil = (raw.crude_oil || 0) + crude;
            raw.water     = (raw.water || 0) + ref * OIL.waterPerRefinery;
            html += `<div class="result-group-title">🛢️ Petróleo (aceite avanzado + cracking)</div>`;
            html += resRow("⛽", "Gas de petróleo requerido", `${fmt(gas)} <span class="exact">/s</span>`, null);
            html += resRow("🏭", "Refinerías", `${Math.ceil(ref - 1e-9)} <span class="exact">(${fmt(ref)})</span>`, null);
            html += resRow("⚗️", "Cracking de pesado (plantas)", `${Math.ceil(hc - 1e-9)} <span class="exact">(${fmt(hc)})</span>`, null);
            html += resRow("⚗️", "Cracking de ligero (plantas)", `${Math.ceil(lc - 1e-9)} <span class="exact">(${fmt(lc)})</span>`, null);
        }

        /* -- Materia prima -- */
        const rawOrder = ["iron_ore", "copper_ore", "stone", "coal", "crude_oil", "water"];
        const rawKeys = rawOrder.filter((k) => raw[k] > 1e-9);
        if (rawKeys.length) {
            html += `<div class="result-group-title">📦 Materia prima (entrada)</div>`;
            rawKeys.forEach((k) => {
                let sub = null;
                if (k === "iron_ore" || k === "copper_ore" || k === "coal" || k === "stone") {
                    const belts = raw[k] / BELT_TP[tiers.belt];
                    sub = `${fmt(belts)} cintas <span class="pill">${tiers.belt}</span>`;
                }
                html += resRow(icon(k), name(k), `${fmt(raw[k])} <span class="exact">/s</span>`, sub);
            });
        }

        /* -- Cintas para el producto final -- */
        const outBelts = ratePerSec / BELT_TP[tiers.belt];
        html += `<div class="result-group-title">➡️ Salida</div>`;
        html += resRow(icon(targetKey), name(targetKey),
            `${fmt(ratePerSec)} <span class="exact">/s</span>`,
            `${fmt(outBelts)} cintas ${tiers.belt} · una cinta ${tiers.belt} lleva ${BELT_TP[tiers.belt]}/s`);

        document.getElementById("fa-results").innerHTML = html;
    }

    /* -----------------------------------------------------------------------
       Wiring de la interfaz
    ----------------------------------------------------------------------- */
    function readTiers() {
        return {
            furnace:   document.getElementById("fa-furnace").value,
            assembler: document.getElementById("fa-assembler").value,
            belt:      document.querySelector('input[name="belt"]:checked').value,
        };
    }

    function run() {
        const target = document.getElementById("fa-product").value;
        let rate = parseFloat(document.getElementById("fa-rate").value);
        if (!isFinite(rate) || rate <= 0) rate = 0;
        const unit = document.querySelector('input[name="unit"]:checked').value; // s | min
        const perSec = unit === "min" ? rate / 60 : rate;
        if (perSec <= 0) {
            document.getElementById("fa-results").innerHTML =
                '<p class="empty-hint">Introduce una cantidad objetivo mayor que 0.</p>';
            return;
        }
        render(target, perSec, readTiers(), unit);
    }

    function init() {
        const ids = ["fa-product", "fa-rate", "fa-furnace", "fa-assembler"];
        ids.forEach((id) => {
            const el = document.getElementById(id);
            el.addEventListener("input", run);
            el.addEventListener("change", run);
        });
        document.querySelectorAll('input[name="unit"], input[name="belt"]')
            .forEach((el) => el.addEventListener("change", run));
        document.getElementById("fa-form").addEventListener("submit", (e) => { e.preventDefault(); run(); });
        run();
    }

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
    else init();
})();
