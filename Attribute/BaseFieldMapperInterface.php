<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute;

use EveryWorkflow\DataFormBundle\Field\AbstractFieldInterface;

interface BaseFieldMapperInterface
{
    public function map(BaseAttributeInterface $attribute): AbstractFieldInterface;
}
