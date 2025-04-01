<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Page Language Modes',
    'description' => 'Change languages modes - "free, fallback, strict" individually for pages',
    'category' => 'fe',
    'author' => 'Andreas Sommer',
    'author_email' => 'sommer@belsignum.com',
    'state' => 'beta',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Belsignum\\Languagemodes\\' => 'Classes/',
        ],
    ],
];
