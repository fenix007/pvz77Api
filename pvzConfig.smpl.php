<?php

return [
    'url' => 'http://dost2.ads1.ru/api/',
    'login' => '',
    'password' => md5(''),
    'cacheEnabled' => true,
    'cacheTime' => 86400,
    'logEnabled' => false , //TODO: check guzzle emptry response when logEnabled and no cache
    'guzzleConfig' => [
        'force_ip_resolve' => 'v4'
    ]
];
