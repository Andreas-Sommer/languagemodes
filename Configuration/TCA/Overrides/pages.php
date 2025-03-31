<?php

defined('TYPO3') || die();

call_user_func(function () {
    $GLOBALS['TCA']['pages']['columns']['tx_languagemodes_mode'] = [
        'exclude' => 1,
        'l10n_mode' => 'exclude',
        'label' => 'LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:pages.tx_languagemodes_mode',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:pages.tx_languagemodes_mode.I.0', ''],
                ['LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:pages.tx_languagemodes_mode.I.1', 'strict'],
                ['LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:pages.tx_languagemodes_mode.I.2', 'fallback'],
                ['LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:pages.tx_languagemodes_mode.I.3', 'free'],
            ],
            'default' => '',
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_languagemodes_mode',
        '',
        'after:l18n_cfg'
    );
});
