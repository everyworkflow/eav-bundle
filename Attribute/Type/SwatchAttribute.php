<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Attribute\Type;

class SwatchAttribute extends SelectAttribute implements SwatchAttributeInterface
{
    protected string $attributeType = 'swatch_attribute';
}
