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

class EntityGridConfig extends DataGridConfig implements EntityGridConfigInterface
{
    public function __construct(DataObjectInterface $dataObject, ActionFactoryInterface $actionFactory)
    {
        parent::__construct($dataObject, $actionFactory);
        $this->dataObject->setDataIfNot(self::KEY_IS_FILTER_ENABLED, true);
        $this->dataObject->setDataIfNot(self::KEY_IS_COLUMN_SETTING_ENABLED, true);
    }

    public function getActiveColumns(): array
    {
        return array_merge(
            ['_id', 'code', 'name', 'class', 'status', 'created_at', 'updated_at'],
            parent::getFilterableColumns()
        );
    }

    public function getFilterableColumns(): array
    {
        return array_merge(
            ['_id', 'code', 'name', 'class', 'status', 'created_at', 'updated_at'],
            parent::getFilterableColumns()
        );
    }

    public function getHeaderActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Create new entity',
                'path' => '/system/entity/create',
            ]),
        ], parent::getHeaderActions());
    }

    public function getRowActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Edit',
                'path' => '/system/entity/{_id}/edit',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/entity/{_id}/delete',
                'confirm_message' => 'Are you sure, you want to delete this item?',
            ]),
        ], parent::getBulkActions());
    }


    public function getBulkActions(): array
    {
        return array_merge([
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Enable',
                'path' => '/system/entity/enable/{_id}',
            ]),
            $this->getActionFactory()->create(ButtonAction::class, [
                'label' => 'Disable',
                'path' => '/system/entity/disable/{_id}',
            ]),
            $this->getActionFactory()->create(ConfirmedActionButton::class, [
                'label' => 'Delete',
                'path' => '/system/entity/delete/{_id}',
                'confirm_message' => 'Are you sure, you want to delete all selected item(s)?',
            ]),
        ], parent::getBulkActions());
    }
}
