<?php

namespace Belsignum\Languagemodes\Xclass;

use TYPO3\CMS\Core\Site\Entity\Site;

class SiteConfiguration extends \TYPO3\CMS\Core\Configuration\SiteConfiguration
{
    protected bool $individualLanguageModeProcessed = false;

    public function hasIndividualLanguageModeProcessed(): bool
    {
        return $this->individualLanguageModeProcessed;
    }

    public function setIndividualLanguageModeProcessed(bool $individualLanguageModeProcessed): void
    {
        $this->individualLanguageModeProcessed = $individualLanguageModeProcessed;
    }

    public function getFirstLevelCache(): array
    {
        return $this->firstLevelCache;
    }

    public function setFirstLevelCache(array $firstLevelCache): void
    {
        $this->firstLevelCache = $firstLevelCache;
    }
}
