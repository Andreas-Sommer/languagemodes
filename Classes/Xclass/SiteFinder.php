<?php

namespace Belsignum\Languagemodes\Xclass;

use Belsignum\BsdAccmgr\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\SiteConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;

class SiteFinder extends \TYPO3\CMS\Core\Site\SiteFinder
{
    protected ?ServerRequest $request;
    public function __construct(?SiteConfiguration $siteConfiguration = null)
    {
        parent::__construct($siteConfiguration);
        if ($this->siteConfiguration->hasIndividualLanguageModeProcessed() === false) {
            $this->request = $GLOBALS['TYPO3_REQUEST'] ?? null;
            $this->modifyPageLanguageMode();
        }
    }

    private function modifyPageLanguageMode(): void
    {
        if (
            $this->request !== null
            && ($routing = $this->request->getAttribute('routing')) !== null
            && ($language = $this->request->getAttribute('language')) !== null
        ) {
            // future request does not need any calculation
            $this->siteConfiguration->setIndividualLanguageModeProcessed(true);

            if (($languageId = $language->getLanguageId()) === 0) {
                return; // stop as for default language we need no further calculation
            }

            $pid = $routing->getPageId();
            $site = $this->request->getAttribute('site');
            $identifier = $site->getIdentifier();
            $rootPageId = $site->getRootPageId();
            $siteConfig = $site->getConfiguration();
            $mode = $this->getPageLanguageMode($pid, $languageId);

            if (
                $mode === false || $mode === ''
                || $language->getFallbackType() === $mode
                || in_array($mode, ['free', 'fallback', 'strict'], true) === false) {
                return;
            }

            $siteConfig['languages'][$languageId]['fallbackType'] = $mode;
            // create new Site object
            $newSite = new Site(
                $identifier,
                $rootPageId,
                $siteConfig
            );
            $this->sites[$identifier] = $newSite;

            // get new SiteLanguage
            $newSiteLanguage = $newSite->getLanguageById($languageId);

            // update flc
            $firstLevelCache = $this->siteConfiguration->getFirstLevelCache();
            $firstLevelCache[$identifier] = $newSite;
            $this->siteConfiguration->setFirstLevelCache($firstLevelCache);

            // update request
            $GLOBALS['TYPO3_REQUEST'] = $this->request
                ->withAttribute('site', $newSite)
                ->withAttribute('language', $newSiteLanguage);

            // Create new LanguageAspect
            $languageAspect = new LanguageAspect(
                $languageId,
                $languageId,
                LanguageAspect::OVERLAYS_ON_WITH_FLOATING,
                $newSiteLanguage->getFallbackLanguageIds()
            );

            // Update Context
            /** @var Context $context */
            $context = GeneralUtility::makeInstance(Context::class);
            $context->setAspect('language', $languageAspect);
            $GLOBALS['TYPO3_CONTEXT'] = $context;
        };
    }

    protected function getPageLanguageMode(int $pid, int $languageId): false|string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');

        return $queryBuilder
            ->select('tx_languagemodes_mode')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageId, Connection::PARAM_INT))
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();
    }

}
