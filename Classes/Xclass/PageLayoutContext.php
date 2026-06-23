<?php

declare(strict_types=1);

namespace Belsignum\Languagemodes\Xclass;

use Belsignum\Languagemodes\Service\PageLanguageModeService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageLayoutContext extends \TYPO3\CMS\Backend\View\PageLayoutContext
{
    /**
     * @var array<int, string|null>
     */
    private array $pageLanguageModeOverrides = [];

    public function getAllowNewContent(): bool
    {
        if ($this->isFreePageLanguageModeActive()) {
            return true;
        }

        return parent::getAllowNewContent();
    }

    public function getLanguageModeLabelClass(): string
    {
        if ($this->getPageLanguageModeOverride() === 'free') {
            return 'info';
        }

        return parent::getLanguageModeLabelClass();
    }

    public function getLanguageMode(): string
    {
        $contentLanguageMode = $this->getLocalizedContentLanguageModeLabel($this->getContentLanguageModeIdentifier());
        $pageLanguageModeOverride = $this->getPageLanguageModeOverride();

        if ($pageLanguageModeOverride === null) {
            return $contentLanguageMode;
        }

        $pageLanguageModeLabel = $this->getLocalizedPageLanguageModeOverrideLabel($pageLanguageModeOverride);
        if ($contentLanguageMode === '') {
            return $pageLanguageModeLabel;
        }

        return $contentLanguageMode . ' / ' . $pageLanguageModeLabel;
    }

    public function getLanguageModeIdentifier(): string
    {
        if ($this->isFreePageLanguageModeActive()) {
            return 'free';
        }

        return $this->getContentLanguageModeIdentifier();
    }

    public function isFreePageLanguageModeActive(): bool
    {
        return ($this->getConfiguredPageLanguageMode() ?? $this->siteLanguage->getFallbackType()) === 'free';
    }

    private function getContentLanguageModeIdentifier(): string
    {
        $contentRecordsPerColumn = $this->contentFetcher->getContentRecordsPerColumn(
            null,
            $this->siteLanguage->getLanguageId()
        );
        $contentRecords = empty($contentRecordsPerColumn) ? [] : array_merge(...$contentRecordsPerColumn);
        $translationData = $this->contentFetcher->getTranslationData(
            $contentRecords,
            $this->siteLanguage->getLanguageId()
        );

        return $translationData['mode'] ?? '';
    }

    private function getPageLanguageModeOverride(): ?string
    {
        $configuredPageLanguageMode = $this->getConfiguredPageLanguageMode();
        if ($configuredPageLanguageMode === null || $configuredPageLanguageMode === $this->siteLanguage->getFallbackType()) {
            return null;
        }

        return $configuredPageLanguageMode;
    }

    private function getConfiguredPageLanguageMode(): ?string
    {
        $languageId = $this->siteLanguage->getLanguageId();
        if (!array_key_exists($languageId, $this->pageLanguageModeOverrides)) {
            $this->pageLanguageModeOverrides[$languageId] = GeneralUtility::makeInstance(PageLanguageModeService::class)
                ->getModeForLocalizedPage($this->getPageId(), $languageId);
        }

        return $this->pageLanguageModeOverrides[$languageId];
    }

    private function getLocalizedContentLanguageModeLabel(string $languageModeIdentifier): string
    {
        return match ($languageModeIdentifier) {
            'mixed' => $this->getLanguageService()->sL(
                'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:languageModeMixed'
            ),
            'connected' => $this->getLanguageService()->sL(
                'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:languageModeConnected'
            ),
            'free' => $this->getLanguageService()->sL(
                'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:languageModeFree'
            ),
            default => '',
        };
    }

    private function getLocalizedPageLanguageModeOverrideLabel(string $pageLanguageModeOverride): string
    {
        return $this->getLanguageService()->sL(
            'LLL:EXT:languagemodes/Resources/Private/Language/locallang_db.xlf:backend.languageModeOverride.'
            . $pageLanguageModeOverride
        );
    }
}
