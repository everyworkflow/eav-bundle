<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Form;

use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactoryInterface;
use EveryWorkflow\DataFormBundle\Factory\FormFieldFactoryInterface;
use EveryWorkflow\DataFormBundle\Field\Select\Option;
use EveryWorkflow\DataFormBundle\Field\Select\OptionInterface;
use EveryWorkflow\DataFormBundle\Model\Form;
use EveryWorkflow\EavBundle\Document\EntityDocumentInterface;
use EveryWorkflow\EavBundle\Model\EavConfigProviderInterface;
use EveryWorkflow\EavBundle\Repository\EntityRepositoryInterface;

class AttributeForm extends Form implements AttributeFormInterface
{
    protected EntityRepositoryInterface $entityRepository;
    protected EavConfigProviderInterface $eavConfigProvider;
    protected FieldOptionFactoryInterface $fieldOptionFactory;

    public function __construct(
        DataObjectInterface $dataObject,
        FormFieldFactoryInterface $formFieldFactory,
        EntityRepositoryInterface $entityRepository,
        EavConfigProviderInterface $eavConfigProvider,
        FieldOptionFactoryInterface $fieldOptionFactory
    ) {
        parent::__construct($dataObject, $formFieldFactory);
        $this->entityRepository = $entityRepository;
        $this->eavConfigProvider = $eavConfigProvider;
        $this->fieldOptionFactory = $fieldOptionFactory;
    }

    protected function getEntityCodeOptions(): array
    {
        $options = [];
        $sortOrder = 1;
        try {
            $entities = $this->entityRepository->find();
        } catch (\ReflectionException $e) {
            $entities = [];
        }
        /** @var EntityDocumentInterface $item */
        foreach ($entities as $item) {
            /** @var OptionInterface $option */
            $option = $this->fieldOptionFactory->create(Option::class, [
                'key' => $item->getCode(),
                'value' => $item->getName(),
                'sort_order' => $sortOrder,
            ]);
            ++$sortOrder;
            $options[] = $option;
        }

        return $options;
    }

    protected function getAttributeTypeOptions(): array
    {
        $options = [];
        $sortOrder = 1;
        foreach ($this->eavConfigProvider->get('attribute_types') as $attCode => $attClass) {
            /** @var OptionInterface $option */
            $option = $this->fieldOptionFactory->create(Option::class, [
                'key' => $attCode,
                'value' => str_replace('_', ' ', ucwords($attCode)),
                'sort_order' => $sortOrder,
            ]);
            ++$sortOrder;
            $options[] = $option;
        }

        return $options;
    }

    public function getFields(): array
    {
        $fields = [
            $this->formFieldFactory->createField([
                'label' => 'UUID',
                'name' => '_id',
                'is_readonly' => true,
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Entity code',
                'name' => 'entity_code',
                'field_type' => 'select_field',
                'options' => $this->getEntityCodeOptions(),
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Code',
                'name' => 'code',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Name',
                'name' => 'name',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Type',
                'name' => 'type',
                'field_type' => 'select_field',
                'options' => $this->getAttributeTypeOptions(),
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Class',
                'name' => 'class',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Status',
                'name' => 'status',
                'field_type' => 'select_field',
                'options' => [
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'enable',
                        'value' => 'Enable',
                    ]),
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'disable',
                        'value' => 'Disable',
                    ]),
                ],
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Is required',
                'name' => 'is_required',
                'field_type' => 'switch_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Is readonly',
                'name' => 'is_readonly',
                'field_type' => 'switch_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Is used in grid',
                'name' => 'is_used_in_grid',
                'field_type' => 'switch_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Is used in form',
                'name' => 'is_used_in_form',
                'field_type' => 'switch_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Sort order',
                'name' => 'sort_order',
                'field_type' => 'text_field',
                'input_type' => 'number',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Created at',
                'name' => 'created_at',
                'is_readonly' => true,
                'field_type' => 'date_time_picker_field',
            ]),
            $this->formFieldFactory->createField([
                'label' => 'Updated at',
                'name' => 'updated_at',
                'is_readonly' => true,
                'field_type' => 'date_time_picker_field',
            ]),
        ];

        $sortOrder = 5;
        foreach ($fields as $field) {
            $field->setSortOrder($sortOrder++);
        }

        return array_merge($fields, parent::getFields());
    }
}
