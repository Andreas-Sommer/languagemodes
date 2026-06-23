<?php

declare(strict_types=1);

namespace Belsignum\Languagemodes\Xclass;

use TYPO3\CMS\Core\Type\Bitmask\Permission;

class GridColumnItem extends \TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem
{
    public function isDragAndDropAllowed(): bool
    {
        if (
            !method_exists($this->context, 'isFreePageLanguageModeActive')
            || !$this->context->isFreePageLanguageModeActive()
        ) {
            return parent::isDragAndDropAllowed();
        }

        $backendUser = $this->getBackendUser();
        if ($backendUser->isAdmin()) {
            return true;
        }

        $pageRecord = $this->context->getPageRecord();

        return ((int)($this->record['editlock'] ?? 0) === 0 && (int)($pageRecord['editlock'] ?? 0) === 0)
            && $backendUser->doesUserHaveAccess($pageRecord, Permission::CONTENT_EDIT)
            && $backendUser->checkAuthMode($this->table, $this->getTypeColumn(), $this->getRecordType());
    }
}
