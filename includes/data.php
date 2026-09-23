<?php
/**
 * data.php — Base de datos de recetas de Factorio: Space Age (esenciales early game).
 *
 * Fuente única de verdad. Se usa para:
 *   - Renderizar el selector y las tarjetas de referencia (en PHP).
 *   - Alimentar el motor de cálculo del navegador (json_encode -> calc.js).
 *
 * Recetas verificadas contra la wiki oficial y reconciliadas por costo hasta el crudo.
 * Los tiempos ("t") están en segundos a velocidad de máquina 1.
 * "out"/"in" están en unidades por craft.
 */

/* --------------------------------------------------------------------------
 *  METADATOS DE ÍTEMS  (nombre en español + emoji para el icono)
 * ------------------------------------------------------------------------ */
$ITEMS = [
    // Recursos crudos
    'iron_ore'                 => ['n' => 'Mineral de hierro',     'e' => '⛏️'],
    'copper_ore'               => ['n' => 'Mineral de cobre',      'e' => '🟠'],
    'stone'                    => ['n' => 'Piedra',                'e' => '🪨'],
    'coal'                     => ['n' => 'Carbón',                'e' => '⚫'],
    'water'                    => ['n' => 'Agua',                  'e' => '💧'],
    'crude_oil'                => ['n' => 'Petróleo crudo',        'e' => '🛢️'],
    'petroleum_gas'            => ['n' => 'Gas de petróleo',       'e' => '⛽'],
    // Placas
    'iron_plate'               => ['n' => 'Placa de hierro',       'e' => '⬜'],
    'copper_plate'             => ['n' => 'Placa de cobre',        'e' => '🟧'],
    'steel_plate'              => ['n' => 'Placa de acero',        'e' => '◾'],
    'stone_brick'              => ['n' => 'Ladrillo de piedra',    'e' => '🧱'],
    // Circuitos
    'copper_cable'             => ['n' => 'Cable de cobre',        'e' => '🧵'],
    'electronic_circuit'       => ['n' => 'Circuito verde',        'e' => '🟩'],
    'advanced_circuit'         => ['n' => 'Circuito rojo',         'e' => '🟥'],
    'processing_unit'          => ['n' => 'Circuito azul',         'e' => '🟦'],
    // Componentes
    'iron_gear_wheel'          => ['n' => 'Engranaje',             'e' => '⚙️'],
    'pipe'                     => ['n' => 'Tubería',               'e' => '🔧'],
    'transport_belt'           => ['n' => 'Cinta transportadora', 'e' => '➡️'],
    'inserter'                 => ['n' => 'Insertador',            'e' => '🦾'],
    'engine_unit'              => ['n' => 'Unidad de motor',       'e' => '🔩'],
    // Militar (intermedios)
    'firearm_magazine'         => ['n' => 'Cargador estándar',     'e' => '🔫'],
    'piercing_rounds_magazine' => ['n' => 'Cargador perforante',  'e' => '🎯'],
    'grenade'                  => ['n' => 'Granada',               'e' => '💣'],
    'wall'                     => ['n' => 'Muro de piedra',        'e' => '🧱'],
    // Química
    'plastic_bar'              => ['n' => 'Plástico',              'e' => '⬜'],
    'sulfur'                   => ['n' => 'Azufre',                'e' => '🟡'],
    'sulfuric_acid'            => ['n' => 'Ácido sulfúrico',       'e' => '🧪'],
    // Ciencia
    'automation_science_pack'  => ['n' => 'Ciencia roja (automatización)', 'e' => '🔴'],
    'logistic_science_pack'    => ['n' => 'Ciencia verde (logística)',     'e' => '🟢'],
    'military_science_pack'    => ['n' => 'Ciencia militar',               'e' => '🔘'],
    'chemical_science_pack'    => ['n' => 'Ciencia azul (química)',        'e' => '🔵'],
];

/* --------------------------------------------------------------------------
 *  RECURSOS CRUDOS  (terminan la expansión; el gas se resuelve aparte)
 * ------------------------------------------------------------------------ */
$RAW = ['iron_ore', 'copper_ore', 'stone', 'coal', 'water', 'crude_oil', 'petroleum_gas'];

/* --------------------------------------------------------------------------
 *  RECETAS  (clave = ítem principal producido)
 *  cat: smelt | assembly | chem
 * ------------------------------------------------------------------------ */
$RECIPES = [
    // ---- Fundición ----
    'iron_plate'   => ['cat' => 'smelt', 't' => 3.2, 'out' => ['iron_plate' => 1],   'in' => ['iron_ore' => 1]],
    'copper_plate' => ['cat' => 'smelt', 't' => 3.2, 'out' => ['copper_plate' => 1], 'in' => ['copper_ore' => 1]],
    'steel_plate'  => ['cat' => 'smelt', 't' => 16,  'out' => ['steel_plate' => 1],  'in' => ['iron_plate' => 5]],
    'stone_brick'  => ['cat' => 'smelt', 't' => 3.2, 'out' => ['stone_brick' => 1],  'in' => ['stone' => 2]],

    // ---- Circuitos ----
    'copper_cable'       => ['cat' => 'assembly', 't' => 0.5, 'out' => ['copper_cable' => 2],       'in' => ['copper_plate' => 1]],
    'electronic_circuit' => ['cat' => 'assembly', 't' => 0.5, 'out' => ['electronic_circuit' => 1], 'in' => ['iron_plate' => 1, 'copper_cable' => 3]],
    'advanced_circuit'   => ['cat' => 'assembly', 't' => 6,   'out' => ['advanced_circuit' => 1],   'in' => ['electronic_circuit' => 2, 'copper_cable' => 4, 'plastic_bar' => 2]],
    'processing_unit'    => ['cat' => 'assembly', 't' => 10,  'out' => ['processing_unit' => 1],    'in' => ['electronic_circuit' => 20, 'advanced_circuit' => 2, 'sulfuric_acid' => 5]],

    // ---- Componentes ----
    'iron_gear_wheel' => ['cat' => 'assembly', 't' => 0.5, 'out' => ['iron_gear_wheel' => 1], 'in' => ['iron_plate' => 2]],
    'pipe'            => ['cat' => 'assembly', 't' => 0.5, 'out' => ['pipe' => 1],            'in' => ['iron_plate' => 1]],
    'transport_belt'  => ['cat' => 'assembly', 't' => 0.5, 'out' => ['transport_belt' => 2],  'in' => ['iron_gear_wheel' => 1, 'iron_plate' => 1]],
    'inserter'        => ['cat' => 'assembly', 't' => 0.5, 'out' => ['inserter' => 1],        'in' => ['iron_gear_wheel' => 1, 'electronic_circuit' => 1, 'iron_plate' => 1]],
    'engine_unit'     => ['cat' => 'assembly', 't' => 10,  'out' => ['engine_unit' => 1],     'in' => ['steel_plate' => 1, 'iron_gear_wheel' => 1, 'pipe' => 2]],

    // ---- Militar (intermedios) ----
    'firearm_magazine'         => ['cat' => 'assembly', 't' => 1, 'out' => ['firearm_magazine' => 1],         'in' => ['iron_plate' => 4]],
    'piercing_rounds_magazine' => ['cat' => 'assembly', 't' => 3, 'out' => ['piercing_rounds_magazine' => 1], 'in' => ['firearm_magazine' => 1, 'steel_plate' => 1, 'copper_plate' => 5]],
    'grenade'                  => ['cat' => 'assembly', 't' => 8, 'out' => ['grenade' => 1],                  'in' => ['iron_plate' => 5, 'coal' => 10]],
    'wall'                     => ['cat' => 'assembly', 't' => 0.5, 'out' => ['wall' => 1],                   'in' => ['stone_brick' => 5]],

    // ---- Química (plantas químicas, velocidad 1) ----
    'plastic_bar'   => ['cat' => 'chem', 't' => 1, 'out' => ['plastic_bar' => 2],   'in' => ['coal' => 1, 'petroleum_gas' => 20]],
    'sulfur'        => ['cat' => 'chem', 't' => 1, 'out' => ['sulfur' => 2],        'in' => ['petroleum_gas' => 30, 'water' => 30]],
    'sulfuric_acid' => ['cat' => 'chem', 't' => 1, 'out' => ['sulfuric_acid' => 50], 'in' => ['sulfur' => 5, 'iron_plate' => 1, 'water' => 100]],

    // ---- Ciencia ----
    'automation_science_pack' => ['cat' => 'assembly', 't' => 5,  'out' => ['automation_science_pack' => 1], 'in' => ['copper_plate' => 1, 'iron_gear_wheel' => 1]],
    'logistic_science_pack'   => ['cat' => 'assembly', 't' => 6,  'out' => ['logistic_science_pack' => 1],   'in' => ['inserter' => 1, 'transport_belt' => 1]],
    'military_science_pack'   => ['cat' => 'assembly', 't' => 10, 'out' => ['military_science_pack' => 2],   'in' => ['grenade' => 1, 'piercing_rounds_magazine' => 1, 'wall' => 2]],
    'chemical_science_pack'   => ['cat' => 'assembly', 't' => 24, 'out' => ['chemical_science_pack' => 2],   'in' => ['sulfur' => 1, 'engine_unit' => 2, 'advanced_circuit' => 3]],
];

/* --------------------------------------------------------------------------
 *  SELECTOR DE PRODUCTOS  (agrupado en optgroups)
 * ------------------------------------------------------------------------ */
$PRODUCT_GROUPS = [
    'Fundición'   => ['iron_plate', 'copper_plate', 'steel_plate', 'stone_brick'],
    'Circuitos'   => ['copper_cable', 'electronic_circuit', 'advanced_circuit', 'processing_unit'],
    'Componentes' => ['iron_gear_wheel', 'pipe', 'transport_belt', 'inserter', 'engine_unit'],
    'Química'      => ['plastic_bar', 'sulfur', 'sulfuric_acid'],
    'Ciencia'     => ['automation_science_pack', 'logistic_science_pack', 'military_science_pack', 'chemical_science_pack'],
];

/* --------------------------------------------------------------------------
 *  TARJETAS DE REFERENCIA  (ratios "de memoria" ya calculados)
 * ------------------------------------------------------------------------ */
$RATIO_CARDS = [
    [
        'title' => 'Fundición 1:1',
        'emoji' => '🔥',
        'ratio' => '24 hornos → 1 cinta amarilla',
        'body'  => 'Un horno eléctrico/de acero funde 1 placa cada 1.6 s (0.625/s). <b>24 hornos</b> saturan una cinta amarilla (15/s); <b>48</b> una roja (30/s). El hierro y el cobre van 1:1 con su mineral.',
        'rows'  => [
            ['24 hornos', '= 15 placas/s (cinta amarilla)'],
            ['48 hornos', '= 30 placas/s (cinta roja)'],
            ['1 mineral', '→ 1 placa'],
        ],
    ],
    [
        'title' => 'Circuito verde 3:2',
        'emoji' => '🟩',
        'ratio' => '3 cable : 2 circuito',
        'body'  => 'El ratio clásico (y el de tu imagen): por cada <b>2</b> ensambladoras de circuito verde necesitas <b>3</b> de cable de cobre. El circuito consume 3 cables por unidad.',
        'rows'  => [
            ['3', 'ensambladoras de cable de cobre'],
            ['2', 'ensambladoras de circuito verde'],
            ['1.5 cobre : 1 hierro', 'placas de entrada'],
        ],
    ],
    [
        'title' => 'Circuito rojo',
        'emoji' => '🟥',
        'ratio' => '⚙ cadena avanzada',
        'body'  => 'El rojo (6 s) es lento, así que hacen falta muchas máquinas alimentándolo. Por cada <b>1</b> ensambladora de circuito rojo:',
        'rows'  => [
            ['2.4', 'circuito verde'],
            ['0.72', 'cable de cobre (extra)'],
            ['12 plástico/s', 'aprox. (según velocidad)'],
        ],
    ],
    [
        'title' => 'Petróleo + cracking',
        'emoji' => '🛢️',
        'ratio' => '20 : 5 : 17',
        'body'  => 'Para convertir <b>todo</b> el crudo en gas de petróleo (aceite avanzado + cracking completo):',
        'rows'  => [
            ['20', 'refinerías (aceite avanzado)'],
            ['5', 'plantas: cracking de pesado'],
            ['17', 'plantas: cracking de ligero'],
        ],
    ],
    [
        'title' => 'Ácido sulfúrico',
        'emoji' => '🧪',
        'ratio' => 'gas → azufre → ácido',
        'body'  => 'Cadena para circuitos azules y baterías. 1 planta de ácido (50/s) se alimenta de:',
        'rows'  => [
            ['1', 'planta de ácido sulfúrico (50/s)'],
            ['0.5', 'planta de azufre'],
            ['7.5 gas/s', 'aprox. de entrada'],
        ],
    ],
    [
        'title' => 'Cintas (throughput)',
        'emoji' => '➡️',
        'ratio' => '15 / 30 / 45 por segundo',
        'body'  => 'Capacidad máxima de cada cinta (ítems por segundo, ambos carriles llenos):',
        'rows'  => [
            ['Amarilla', '15 / s'],
            ['Roja',     '30 / s'],
            ['Azul',     '45 / s'],
        ],
    ],
];
