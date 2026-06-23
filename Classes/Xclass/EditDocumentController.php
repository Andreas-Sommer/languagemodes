<?php

declare(strict_types=1);

namespace Belsignum\Languagemodes\Xclass;

use Belsignum\Languagemodes\Service\PageLanguageModeService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EditDocumentController extends \TYPO3\CMS\Backend\Controller\EditDocumentController
{
    protected function getFreeTranslationMode(int $page, int $column, int $language): bool
    {
        if (GeneralUtility::makeInstance(PageLanguageModeService::class)->isFreeModeActiveForLocalizedPage($page, $language)) {
            return true;
        }

        return parent::getFreeTranslationMode($page, $column, $language);
    }
}
