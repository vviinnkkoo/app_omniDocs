<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Show warnings
    |--------------------------------------------------------------------------
    */
    'show_warnings' => false, // Isključi upozorenja, ubrzava render

    /*
    |--------------------------------------------------------------------------
    | Default paper orientation
    |--------------------------------------------------------------------------
    */
    'orientation' => 'portrait', // ili 'landscape' po potrebi

    /*
    |--------------------------------------------------------------------------
    | Enable remote (https/http) images
    |--------------------------------------------------------------------------
    */
    'defines' => [
        "DOMPDF_ENABLE_REMOTE" => false,
        "DOMPDF_ENABLE_PHP" => false,
        "DOMPDF_ENABLE_HTML5PARSER" => true,
        "DOMPDF_ENABLE_CSS_FLOAT" => true,
        "DOMPDF_TEMP_DIR" => storage_path('dompdf_temp'),
        "DOMPDF_FONT_DIR" => storage_path('fonts/'),
        "DOMPDF_FONT_CACHE" => storage_path('fonts/'),
        "DOMPDF_DPI" => 72, // manji DPI = brži render, manje kvalitete
        "DOMPDF_ENABLE_AUTOLOAD" => true, // brže učitavanje klasa
        "DOMPDF_ENABLE_INLINE_PHP" => false, 
        "DOMPDF_ENABLE_JAVASCRIPT" => false, // ako nema JS u PDF-u
        "DOMPDF_ENABLE_REMOTE_IMAGES" => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Font directories
    |--------------------------------------------------------------------------
    */
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),

    /*
    |--------------------------------------------------------------------------
    | Temp directory
    |--------------------------------------------------------------------------
    */
    'temp_dir' => storage_path('dompdf_temp/'),

    /*
    |--------------------------------------------------------------------------
    | CSS float
    |--------------------------------------------------------------------------
    */
    'enable_css_float' => true,

    /*
    |--------------------------------------------------------------------------
    | HTML5 parser
    |--------------------------------------------------------------------------
    */
    'enable_html5_parser' => true,

];
