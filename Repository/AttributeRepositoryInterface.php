<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\EavBundle\Attribute\BaseAttribute;
use EveryWorkflow\EavBundle\Factory\AttributeFactoryInterface;
use EveryWorkflow\MongoBundle\Factory\DocumentFactoryInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;

interface AttributeRepositoryInterface extends BaseDocumentRepositoryInterface
{
    /**
     * @return AttributeFactoryInterface
     */
    public function getDocumentFactory(): DocumentFactoryInterface;

    /**
     * @param array $filter
     *
     * @return BaseAttribute[]
     */
    public function find(array $filter = [], array $options = []): array;
}
