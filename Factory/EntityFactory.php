<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Factory;

use EveryWorkflow\CoreBundle\Model\DataObjectFactoryInterface;
use EveryWorkflow\EavBundle\Entity\BaseEntityInterface;

class EntityFactory implements EntityFactoryInterface
{
    protected DataObjectFactoryInterface $dataObjectFactory;

    public function __construct(DataObjectFactoryInterface $dataObjectFactory)
    {
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @param string $className
     * @param array $data
     * @param array $attributes
     * @return BaseEntityInterface
     */
    public function create(string $className, array $data = [], array $attributes = []): BaseEntityInterface
    {
        $dataObj = $this->dataObjectFactory->create($data);
        return new $className($dataObj);
    }
}
