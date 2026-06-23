<?php

declare(strict_types=1);

namespace Belsignum\Languagemodes\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class PageLanguageModeService
{
    private const VALID_MODES = [
        'fallback' => true,
        'free' => true,
        'strict' => true,
    ];

    public function getModeForLocalizedPage(int $pageId, int $languageId): ?string
    {
        if ($pageId <= 0 || $languageId <= 0) {
            return null;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');

        $mode = $queryBuilder
            ->select('tx_languagemodes_mode')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq(
                            'l10n_parent',
                            $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                        ),
                        $queryBuilder->expr()->eq(
                            'sys_language_uid',
                            $queryBuilder->createNamedParameter($languageId, Connection::PARAM_INT)
                        )
                    ),
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                        ),
                        $queryBuilder->expr()->eq(
                            'sys_language_uid',
                            $queryBuilder->createNamedParameter($languageId, Connection::PARAM_INT)
                        )
                    )
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!is_string($mode) || $mode === '' || !isset(self::VALID_MODES[$mode])) {
            return null;
        }

        return $mode;
    }

    public function isFreeModeForLocalizedPage(int $pageId, int $languageId): bool
    {
        return $this->getModeForLocalizedPage($pageId, $languageId) === 'free';
    }

    public function getActiveModeForLocalizedPage(int $pageId, int $languageId): ?string
    {
        return $this->getModeForLocalizedPage($pageId, $languageId)
            ?? $this->getSiteConfiguredModeForLanguage($pageId, $languageId);
    }

    public function isFreeModeActiveForLocalizedPage(int $pageId, int $languageId): bool
    {
        return $this->getActiveModeForLocalizedPage($pageId, $languageId) === 'free';
    }

    private function getSiteConfiguredModeForLanguage(int $pageId, int $languageId): ?string
    {
        if ($pageId <= 0 || $languageId <= 0) {
            return null;
        }

        try {
            $mode = GeneralUtility::makeInstance(SiteFinder::class)
                ->getSiteByPageId($pageId)
                ->getLanguageById($languageId)
                ->getFallbackType();
        } catch (SiteNotFoundException) {
            return null;
        }

        if ($mode === '' || !isset(self::VALID_MODES[$mode])) {
            return null;
        }

        return $mode;
    }
}
