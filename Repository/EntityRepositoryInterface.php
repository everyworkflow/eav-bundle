<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;

interface EntityRepositoryInterface extends BaseDocumentRepositoryInterface
{
    public function getSelectOptions(): \MongoDB\Driver\Cursor;

    /**
     * @param string $entityCode
     * @param array $otherFilter
     * @return array|object|null
     */
    public function deleteByCode(string $entityCode, array $otherFilter = []): object|array|null;
}
