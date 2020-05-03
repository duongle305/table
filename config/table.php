<?php
return [
    'resource' => 'cdn',
    'disabled' => [],
    'extensions' => [
        'jquery' => [
            'cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js'
        ],
        'datatable' => [
            'local' => 'assets/cms/js/plugins/tables/datatables/datatables.min.js',
            'cdn' => [
                'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css',
                'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',
            ],
        ],
        'button' => [
            'local' => ['assets/cms/js/plugins/tables/datatables/extensions/buttons.min.js'],
            'cdn' => [],
        ],
        'excel' => [
            'local' => ['assets/cms/js/plugins/tables/datatables/extensions/jszip/jszip.min.js'],
            'cdn' => []
        ],
        'pdf' => [
            'local' => [
                'assets/cms/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js',
                'assets/cms/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js'
            ],
            'cdn' => [],
        ]
    ]
];
