<?php

return [
    'platforms' => [
        'web' => 'Web',
        'api' => 'API'
    ],
    'display_date_timezone' => env('USER_TIMEZONE', 'UTC'),
    'format' => [
        'date' => 'M d, Y',
        'time' => 'h:i A',
        'datetime' => 'M d, Y h:i A',
        'moment_date' => 'MMM D, Y',
        'moment_datetime' => 'MMM D, Y hh:mm A',
        'sql_date' => 'YYYY-MM-DD',
    ],

    'user_role' => [
        'Admin' => 'admin',
        'User' => 'user'
    ],

    'pagination' => [
        'page_no' => 10
    ]

];
