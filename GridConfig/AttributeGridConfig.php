<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\GridConfig;

use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataGridBundle\Factory\ActionFactoryInterface;
use EveryWorkflow\DataGridBundle\Model\Action\ButtonAction;
use EveryWorkflow\DataGridBundle\Model\Action\ConfirmedActionButton;
use EveryWorkflow\DataGridBundle\Model\DataGridConfig;

class AttributeGridConfig extends DataGridConfig implements AttributeGridConfigInterface
{
    protected const GRID_COLUMNS =
        [
            '_id',
            'code',
            'name',
            'entity_code',
            'type',
//            'is_readonly',
//            'is_used_in_grid',
//            'is_used_in_form',
            'created_at',
            'updated_at'
        ];

    public function __construct(DataObjectInterface $dataObject, ActionFactoryInterface $actionFactory)
    {
        parent::__construct($dataObject, $actionFactory);
        $this->dataObject->setDataIfNot(self::KEY_IS_FILTER_ENABLED, true);
        $this->dataObject->setDataIfNot(self::KEY_IS_COLUMN_SETTING_ENABLED, true);
    }

    public function getActiveColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getActiveColumns());
    }

    public function getSortableColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getSortableColumns());
    }

    public function getFilterableColumns(): array
    {
        return array_merge(self::GRID_COLUMNS, parent::getFilterableColumns());
    }

    public function getHeaderActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Create new attribute',
                'path' => '/system/attribute/create',
            ]),
        ], parent::getHeaderActions());
    }

    public function getRowActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Edit',
                'path' => '/system/attribute/{_id}/edit',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/attribute/{_id}/delete',
                'confirm_message' => 'Are you sure, you want to delete this item?',
            ]),
        ], parent::getBulkActions());
    }


    public function getBulkActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Enable',
                'path' => '/system/attribute/enable/{_id}',
            ]),
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Disable',
                'path' => '/system/attribute/disable/{_id}',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/attribute/delete/{_id}',
                'confirm_message' => 'Are you sure, you want to delete all selected item(s)?',
            ]),
        ], parent::getBulkActions());
    }
}
