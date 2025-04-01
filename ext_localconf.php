<?php

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Site\SiteFinder::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\SiteFinder::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Configuration\SiteConfiguration::class] = [
    'className' => \Belsignum\Languagemodes\Xclass\SiteConfiguration::class,
];
