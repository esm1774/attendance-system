<?php

return [
    'exports' => [
        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Here you can specify how big the chunk should be.
        |
        */
        'chunk_size'     => 1000,

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas during export
        |--------------------------------------------------------------------------
        |
        | By default PhpSpreadsheet keeps the formulas as is.
        | When set to `true`, PhpSpreadsheet will calculate the formulas
        | immediately.
        |
        */
        'calculate'      => false,

        /*
        |--------------------------------------------------------------------------
        | CSV settings
        |--------------------------------------------------------------------------
        |
        | Configure e.g. delimiter, enclosure and line ending for CSV exports.
        |
        */
        'csv'            => [
            'delimiter'              => ',',
            'enclosure'              => '"',
            'line_ending'            => PHP_EOL,
            'use_bom'                => false,
            'include_separator_line' => false,
            'excel_compatibility'    => false,
            'output_encoding'        => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheet settings
        |--------------------------------------------------------------------------
        |
        | Configure the worksheet settings for some exports.
        |
        */
        'worksheets' => [
            'page_margin' => [
                'top'    => 0.8,
                'right'  => 0.8,
                'bottom' => 0.8,
                'left'   => 0.8,
            ],
        ],
    ],

    'imports'            => [
        /*
        |--------------------------------------------------------------------------
        | Read Only
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might only be interested in the
        | data that the sheet exists. By default, this package will read
        | all cells, even if they have no value. Setting this to `true`,
        | will only read the cells that have a value.
        |
        */
        'read_only' => true,

        /*
        |--------------------------------------------------------------------------
        | Ignore Empty
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might be interested in rows
        | that actually have data. By default, this package will read
        | all rows, even if they have no data. Setting this to `true`,
        | will skip the empty rows.
        |
        */
        'ignore_empty' => false,

        /*
        |--------------------------------------------------------------------------
        | Heading Row Formatter
        |--------------------------------------------------------------------------
        |
        | Configure the heading row formatter.
        | Available options: none|slug|custom
        |
        */
        'heading_row' => [
            'formatter' => 'slug',
        ],

        /*
        |--------------------------------------------------------------------------
        | CSV settings
        |--------------------------------------------------------------------------
        |
        | Configure e.g. delimiter, enclosure and line ending for CSV imports.
        |
        */
        'csv' => [
            'delimiter'        => ',',
            'enclosure'        => '"',
            'escape_character' => '\\\\',
            'contiguous'       => false,
            'input_encoding'   => 'UTF-8',
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheets settings
        |--------------------------------------------------------------------------
        |
        | Configure the worksheet settings for some imports.
        |
        */
        'worksheets' => [
            'sheet_name' => [
                'default' => 'Worksheet',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Temporary Path
    |--------------------------------------------------------------------------
    |
    | When importing files, we use a temporary file to store the file before
    | reading it. By default, this file will be stored in your system's
    | temporary directory.
    |
    */
    'temporary_path' => sys_get_temp_dir(),

    /*
    |--------------------------------------------------------------------------
    | Temporary Path Remote
    |--------------------------------------------------------------------------
    |
    | When using remote imports (e.g. s3), you might want to download the file
    | to a temporary location before reading it. By default, this file will be
    | stored in your system's temporary directory.
    |
    */
    'temporary_path_remote' => sys_get_temp_dir(),

    /*
    |--------------------------------------------------------------------------
    | Cell Caching
    |--------------------------------------------------------------------------
    |
    | By default, cell caching is enabled. This will significantly improve the
    | performance of the package, as it will cache the cells in memory.
    |
    */
    'cache' => [
        'driver' => 'memory',
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Handler
    |--------------------------------------------------------------------------
    |
    | By default, the import is wrapped in a transaction. This is useful
    | for when an import fails, as the database will be rolled back.
    |
    */
    'transactions' => [
        'handler' => 'db',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autosize
    |--------------------------------------------------------------------------
    |
    | By default, the package will autosize the columns. You can disable
    | this by setting this to false.
    |
    */
    'autosize' => true,
];
