<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute\FieldMapper;

use EveryWorkflow\DataFormBundle\Field\BaseFieldInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Attribute\BaseFieldMapper;
use EveryWorkflow\EavBundle\Attribute\Type\SelectAttributeInterface;

class SelectMapper extends BaseFieldMapper implements SelectMapperInterface
{
    protected string $fieldType = 'select_field';

    public function map(BaseAttributeInterface|SelectAttributeInterface $attribute): BaseFieldInterface
    {
        /** @var \EveryWorkflow\DataFormBundle\Field\SelectFieldInterface $field */
        $field = parent::map($attribute);
        return $field->setOptions($attribute->getOptions());
    }
}
