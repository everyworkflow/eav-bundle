<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute\FieldMapper;

use EveryWorkflow\DataFormBundle\Field\BaseFieldInterface;
use EveryWorkflow\DataFormBundle\Field\TextareaFieldInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Attribute\BaseFieldMapper;

class LongTextMapper extends BaseFieldMapper implements LongTextMapperInterface
{
    protected string $fieldType = 'textarea_field';

    public function map(BaseAttributeInterface $attribute): BaseFieldInterface
    {
        /** @var TextareaFieldInterface $field */
        $field = parent::map($attribute);
        $field->setRowCount(5);

        return $field;
    }
}
