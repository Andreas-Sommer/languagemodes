<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Language Modes',
    'description' => 'Change languages modes - "free, fallback, strict" individually for pages',
    'category' => 'fe',
    'author' => 'Andreas Sommer',
    'author_email' => 'sommer@belsignum.com',
    'state' => 'alpha',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Belsignum\\Languagemodes\\' => 'Classes/',
        ],
    ],
];
