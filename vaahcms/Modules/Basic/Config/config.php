<?php

return [
    "name"=> "Basic",
    "title"=> "Basic is not always basic.",
    "slug"=> "basic",
    "thumbnail"=> "https://img.site/p/300/160",
    "is_dev" => (bool)env('MODULE_BASIC_ENV'),
    "excerpt"=> "description",
    "description"=> "description",
    "download_link"=> "",
    "author_name"=> "suman",
    "author_website"=> "https://vaah.dev",
    "version"=> "0.0.2",
    "is_migratable"=> true,
    "is_sample_data_available"=> true,
    "db_table_prefix"=> "vh_basic_",
    "providers"=> [
        "\\VaahCms\\Modules\\Basic\\Providers\\BasicServiceProvider"
    ],
    "aside-menu-order"=> null
];
