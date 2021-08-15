<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute\FieldMapper;

use EveryWorkflow\DataFormBundle\Field\AbstractFieldInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Attribute\BaseFieldMapper;
use EveryWorkflow\EavBundle\Attribute\Type\SelectAttributeInterface;

class MultiSelectMapper extends BaseFieldMapper implements MultiSelectMapperInterface
{
    protected string $fieldType = 'select_field';

    public function map(BaseAttributeInterface | SelectAttributeInterface $attribute): AbstractFieldInterface
    {
        return parent::map($attribute);
    }
}
