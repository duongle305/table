<?php
return [
    'resource' => 'cdn',
    'disabled' => [], // add name of extension not autoload -> ex: ['jquery']
    'extensions' => [
        'jquery' => [
            'cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js'
        ],
        'datatable' => [
            'cdn' => [
                'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css',
                'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',
            ],
        ],
        'buttons' => [
            'button' => [
                'cdn' => [
                    'https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js',
                    'https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'
                ],
            ],

            'excel' => [
                'cdn' => [
                    'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'
                ]
            ],
            'pdf' => [
                'cdn' => [
                    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
                ],
            ],
            'print' => [
                'cdn' => [
                    'https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js'
                ]
            ],
            'colvis' => [
                'cdn' => [
                    'https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js'
                ]
            ]
        ],
    ]
];
