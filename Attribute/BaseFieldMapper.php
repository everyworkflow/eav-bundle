<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute;

use EveryWorkflow\DataFormBundle\Factory\FormFieldFactoryInterface;
use EveryWorkflow\DataFormBundle\Field\AbstractFieldInterface;

class BaseFieldMapper implements BaseFieldMapperInterface
{
    protected string $fieldType = 'text_field';

    protected FormFieldFactoryInterface $formFieldFactory;

    public function __construct(FormFieldFactoryInterface $formFieldFactory)
    {
        $this->formFieldFactory = $formFieldFactory;
    }

    public function map(BaseAttributeInterface $attribute): AbstractFieldInterface
    {
        $data = $attribute->toArray();
        $data['field_type'] = $this->fieldType;

        return $this->formFieldFactory->createField($data)
            ->setName($attribute->getCode())
            ->setLabel($attribute->getName());
    }
}
