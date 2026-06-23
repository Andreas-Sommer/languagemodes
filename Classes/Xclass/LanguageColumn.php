<?php

declare(strict_types=1);

namespace Belsignum\Languagemodes\Xclass;

class LanguageColumn extends \TYPO3\CMS\Backend\View\BackendLayout\Grid\LanguageColumn
{
    public function getTranslationData(): array
    {
        $translationData = parent::getTranslationData();

        if (
            method_exists($this->context, 'isFreePageLanguageModeActive')
            && $this->context->isFreePageLanguageModeActive()
        ) {
            // The effective free language mode makes the backend layout behave like TYPO3's native free content mode.
            $translationData['mode'] = 'free';
        }

        return $translationData;
    }
}
