<?php

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Site\SiteFinder::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\SiteFinder::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Configuration\SiteConfiguration::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\SiteConfiguration::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\View\PageLayoutContext::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\PageLayoutContext::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\View\BackendLayout\Grid\LanguageColumn::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\LanguageColumn::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\GridColumnItem::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\EditDocumentController::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\EditDocumentController::class,
];
