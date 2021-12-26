<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Tests\Unit;

use EveryWorkflow\DataGridBundle\Tests\BaseGridTestCase;
use EveryWorkflow\EavBundle\Model\EavConfigProvider;
use EveryWorkflow\EavBundle\Model\EavConfigProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseEavTestCase extends BaseGridTestCase
{
    protected function getEavConfigProvider(): EavConfigProviderInterface
    {
        return new EavConfigProvider($this->getContainer()->getParameter('eav'));
    }
}
