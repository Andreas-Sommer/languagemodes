<?php
namespace Belsignum\Languagemodes\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class LanguageModeModifier
{
    public static function apply(array &$params, TypoScriptFrontendController $tsfe): void
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        if (!isset($request)) {
            return;
        }

        $siteLanguage = $request->getAttribute('language');
        if (!method_exists($siteLanguage, 'setFallbackType')) {
            throw new \RuntimeException('The method setFallbackType() is missing on SiteLanguage. The core patch might be missing.', 1711870491);
        }

        $pageId = (int)($tsfe->id ?? 0);
        $languageId = $tsfe->language->getLanguageId() ?? 0;

        if (!$pageId || !$siteLanguage instanceof SiteLanguage) {
            return;
        }
        // Only apply if a translated language is active (L > 0)
        if ($languageId > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('pages');

            // Get individual language mode configuration
            $mode = $queryBuilder
                ->select('tx_languagemodes_mode')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($pageId, \PDO::PARAM_INT))
                )
                ->execute()
                ->fetchColumn(0);

            if (in_array($mode, ['free', 'fallback', 'strict'], true)) {
                // Set fallback type dynamically
                $siteLanguage->setFallbackType($mode);

                // Inject updated SiteLanguage into PSR request
                $GLOBALS['TYPO3_REQUEST'] = $request->withAttribute('language', $siteLanguage);
            }
        }
    }
}
