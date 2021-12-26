<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\MongoBundle\Document\BaseDocumentInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;

interface AttributeRepositoryInterface extends BaseDocumentRepositoryInterface
{
    /**
     * @return BaseAttributeInterface
     */
    public function create(array $data = []): BaseDocumentInterface;
}
