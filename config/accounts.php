<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Account Identifiers and Codes
    |--------------------------------------------------------------------------
    |
    | This file stores hard-coded account IDs and codes used across the 
    | application. Centralizing these allows for easier maintenance and 
    | dynamic configuration.
    |
    */

    'inventory' => [
        'main' => [
            'id' => 1101,
            'code' => '1-2-1',
        ],
        'finished_goods' => [
            'id' => 97,
            'code' => '1-2-1-1',
        ],
        'wip' => [
            'id' => null, // Dynamic lookups usually
            'code' => '1-2-1-2',
        ],
        'raw_material' => [
            'id' => 840,
            'code' => '1-2-16-1',
        ],
    ],

    'purchase' => [
        'grn_clearing' => [
            'id' => 1708,
            'code' => '2-36-1',
        ],
        'input_gst' => [
            'id' => 1709,
            'code' => '1-5',
        ],
        'wht' => [
            'id' => 1710,
            'code' => '2-36-2',
        ],
    ],

    'sales' => [
        'cogs' => [
            'id' => 1053,
            'code' => '7-1',
        ],
        'output_adjustment' => [
            'id' => 1051,
            'code' => '5-8',
        ],
        'gst_payable' => [
            'id' => 1778,
            'code' => '2-371',
        ],
        'advance_tax_receivable' => [
            'id' => 1777,
            'code' => '1-57-2',
        ],
        'discount' => [
            'id' => 612,
            'code' => '4-1-3',
        ],
        'receivable' => [
            'id' => 521,
            'code' => '3-2-3-8',
        ],
    ],

    'production' => [
        'labour' => [
            'id' => 841,
            'code' => '1-2-16-2',
        ],
        'foh' => [
            'id' => 842,
            'code' => '1-2-16-3',
        ],
        'material' => [
            'id' => 840,
            'code' => '1-2-16-1',
        ],
        'labour_cr' => [
            'id' => 856,
            'code' => '1-2-15-5',
        ],
        'die_mould_cr' => [
            'id' => null,
            'code' => '1-2-15-3',
        ],
        'machine_cr' => [
            'id' => null,
            'code' => '1-2-15-2',
        ],
        'foh_cr' => [
            'id' => null,
            'code' => '1-2-15-4',
        ],
    ],

    'finance' => [
        'bank' => [
            'id' => 806,
            'code' => '1-2-11',
        ],
    ],

    'adjustment' => [
        'inventory' => [
            'id' => 1101,
            'code' => '1-2-1',
        ],
        'gain' => [
            'id' => 1890,
            'code' => '5-9-1',
        ],
        'loss' => [
            'id' => 1891,
            'code' => '4-16-1',
        ],
    ]
];
