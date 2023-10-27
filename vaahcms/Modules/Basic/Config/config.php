<?php

return [
    "name"=> "Basic",
    "title"=> "Basic is not always basic.",
    "slug"=> "basic",
    "thumbnail"=> "https://img.site/p/300/160",
    "is_dev" => env('MODULE_BASIC_ENV')?true:false,
    "excerpt"=> "description",
    "description"=> "description",
    "download_link"=> "",
    "author_name"=> "suman",
    "author_website"=> "https://vaah.dev",
    "version"=> "0.0.1",
    "is_migratable"=> true,
    "is_sample_data_available"=> false,
    "db_table_prefix"=> "vh_basic_",
    "providers"=> [
        "\\VaahCms\\Modules\\Basic\\Providers\\BasicServiceProvider"
    ],
    "aside-menu-order"=> null
];
