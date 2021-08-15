<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle;

use EveryWorkflow\EavBundle\DependencyInjection\EavExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EveryWorkflowEavBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EavExtension();
    }
}
